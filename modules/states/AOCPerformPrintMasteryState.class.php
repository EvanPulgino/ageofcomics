<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * Backend functions used by the performPrintMastery State
 *
 * @EvanPulgino
 */

class AOCPerformPrintMasteryState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    public function getArgs($playerId = null) {
        return [];
    }

    public function stPerformPrintMastery() {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Get the printed comic and its genre
        $printedComicId = $this->game->getGameStateValue(PRINTED_COMIC);
        $comic = $this->game->cardManager->getCard($printedComicId);
        $genreId = $comic->getGenreId();

        // Get the mastery token for the genre
        $masteryToken = $this->game->masteryManager->getMasteryToken($genreId);

        // If the player already has the mastery token, do nothing
        if ($masteryToken->getPlayerId() === $activePlayer->getId()) {
            $this->game->gamestate->nextState("checkUpgrade");
            return;
        }

        // If the player hasn't printed an original comic in the genre, do nothing
        if (!$this->playerHasOriginalComic($activePlayer, $genreId)) {
            $this->game->gamestate->nextState("checkUpgrade");
            return;
        }

        // If the player has more printed comics than the current mastery token owner, claim the token
        if (
            $this->printedComicCount($activePlayer, $genreId) >
            $masteryToken->getComicCount()
        ) {
            $source = clienttranslate("the supply");
            if ($masteryToken->getPlayerId()) {
                $source = $this->game->playerManager
                    ->getPlayer($masteryToken->getPlayerId())
                    ->getName();
            }

            $this->game->masteryManager->claimMasteryToken(
                $activePlayer->getId(),
                $genreId,
                $this->printedComicCount($activePlayer, $genreId)
            );
            $updatedToken = $this->game->masteryManager->getMasteryToken(
                $genreId
            );

            $this->game->notifyAllPlayers(
                "masteryTokenClaimed",
                clienttranslate(
                    '${player_name} gains the ${genre} mastery token from ${source}'
                ),
                [
                    "player" => $activePlayer->getUiData(),
                    "player_name" => $activePlayer->getName(),
                    "genre" => $this->game->formatNotificationString(
                        $comic->getGenre(),
                        $comic->getGenreId()
                    ),
                    "source" => $source,
                    "masteryToken" => $updatedToken->getUiData(),
                ]
            );

            // Increase fans for all mini comics of genre of the new owner
            $this->updateComicsOfNewOwner($activePlayer, $genreId, $comic);
        }

        $this->game->gamestate->nextState("checkUpgrade");
    }

    /**
     * Check if the player has printed at least one original comic in the genre
     *
     * @param AOCPlayer $player The player to check
     * @param int $genreId The genre to check
     * @return bool True if the player has printed an original comic in the genre, false otherwise
     */
    private function playerHasOriginalComic($player, $genreId) {
        $printedOriginals = $this->game->cardManager->getCards(
            CARD_TYPE_COMIC,
            $genreId,
            LOCATION_PLAYER_MAT,
            $player->getId()
        );

        if (count($printedOriginals) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Get the number of printed comics the player has in the genre
     *
     * @param AOCPlayer $player The player to check
     * @param int $genreId The genre to check
     * @return int The number of printed comics the player has in the genre
     */
    private function printedComicCount($player, $genreId) {
        $printedOriginals = $this->game->cardManager->getCards(
            CARD_TYPE_COMIC,
            $genreId,
            LOCATION_PLAYER_MAT,
            $player->getId()
        );
        $printedRipoffs = $this->game->cardManager->getCards(
            CARD_TYPE_RIPOFF,
            $genreId,
            LOCATION_PLAYER_MAT,
            $player->getId()
        );

        return count($printedOriginals) + count($printedRipoffs);
    }

    private function updateComicsOfNewOwner($player, $genreId, $comic) {
        $miniComics = $this->game->miniComicManager->getMiniComicsByPlayerAndGenre(
            $player->getId(),
            $genreId
        );

        foreach ($miniComics as $miniComic) {
            $incomeChange = $this->game->miniComicManager->adjustMiniComicFans(
                $comic,
                1
            );
            $this->game->playerManager->adjustPlayerIncome(
                $player,
                $incomeChange
            );
            $this->game->notifyAllPlayers(
                "moveMiniComic",
                clienttranslate(
                    '${comicName} gains 1 fan from gain of ${genre} mastery token'
                ),
                [
                    "player" => $player->getUiData(),
                    "player_name" => $player->getName(),
                    "comicName" => $this->game->formatNotificationString(
                        $comic->getName(),
                        $comic->getGenreId()
                    ),
                    "genre" => $this->game->formatNotificationString(
                        $miniComic->getGenre(),
                        $miniComic->getGenreId()
                    ),
                    "miniComic" => $this->game->miniComicManager->getMiniComicUiData(
                        $miniComic->getId()
                    ),
                    "incomeChange" => $incomeChange,
                    "fansChange" => 1,
                    "slot" => $comic->getLocationArg(),
                ]
            );
        }
    }
}
