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
 * Backend functions used by the performPrintBonus State
 *
 * @EvanPulgino
 */

class AOCPerformPrintBonusState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    public function getArgs() {
        $printedComicId = $this->game->getGameStateValue(PRINTED_COMIC);
        $comic = $this->game->cardManager->getCard($printedComicId);

        return [
            "comicName" => $this->game->formatNotificationString(
                $comic->getName(),
                $comic->getGenreId()
            ),
            "comicBonus" => $comic->getTypeId() == CARD_TYPE_COMIC ? $comic->getBonus() : null,
        ];
    }

    public function stPerformPrintBonus() {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        $printedComicId = $this->game->getGameStateValue(PRINTED_COMIC);
        $comic = $this->game->cardManager->getCard($printedComicId);

        if ($comic->getTypeId() == CARD_TYPE_COMIC) {
            $bonus = $comic->getBonus();

            switch ($bonus) {
                case PLUS_FOUR_MONEY:
                    $this->plusFourMoney($activePlayer, $comic);
                    break;
                case PLUS_ONE_FAN:
                    $this->plusOneFan($activePlayer, $comic);
                    break;
                case SUPER_TRANSPORT:
                    $this->superTransport($activePlayer, $comic);
                    break;
                case TWO_IDEAS:
                    return;
            }
        }

        $this->game->gamestate->nextState("checkMastery");
    }

    /**
     * The player gains all of the ideas they selected
     *
     * @param int[] $ideas The ideas the player selected (using the genre's key)
     * @return void
     */
    public function confirmGainBonusIdeas($ideas) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // For each idea the player took from the supply, gain that idea
        foreach ($ideas as $ideaGenre) {
            $this->gainIdeaFromSupply($activePlayer, $ideaGenre);
        }

        // Set the state to the next player's turn
        $this->game->gamestate->nextState("checkMastery");
    }

    /**
     * A player gains an idea from the supply
     *
     * @param AOCPlayer $player The player gaining the idea
     * @param int $genre key The genre of the idea to gain
     * @return void
     */
    private function gainIdeaFromSupply($player, $genre) {
        // Adjust the player's ideas
        $this->game->playerManager->adjustPlayerIdeas($player, 1, $genre);

        // Notify all players of the gain
        $this->game->notifyAllPlayers(
            "gainIdeaFromSupply",
            clienttranslate(
                '${player_name} gains a ${genreString} idea from the supply as a bonus for printing a comic'
            ),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "genre" => GENRES[$genre],
                "genreString" => $this->game->formatNotificationString(
                    GENRES[$genre],
                    $genre
                ),
            ]
        );
    }

    private function plusFourMoney($player, $comic) {
        $this->game->playerManager->adjustPlayerMoney($player, 4);
        $this->game->notifyAllPlayers(
            "adjustMoney",
            clienttranslate(
                '${player_name} earns 4 money from printing ${comicName}'
            ),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "amount" => 4,
                "comicName" => $this->game->formatNotificationString(
                    $comic->getName(),
                    $comic->getGenreId()
                ),
            ]
        );
    }

    private function plusOneFan($player, $comic) {
        $incomeChange = $this->game->miniComicManager->adjustMiniComicFans(
            $comic,
            1
        );
        $miniComic = $this->game->miniComicManager->getCorrespondingMiniComic(
            $comic
        );

        $this->game->playerManager->adjustPlayerIncome($player, $incomeChange);
        $this->game->notifyAllPlayers(
            "moveMiniComic",
            clienttranslate('${comicName} gains a bonus of 1 fan'),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "comicName" => $this->game->formatNotificationString(
                    $comic->getName(),
                    $comic->getGenreId()
                ),
                "miniComic" => $miniComic->getUiData(),
                "incomeChange" => $incomeChange,
            ]
        );
    }

    private function superTransport($player, $comic) {
        $this->game->playerManager->adjustPlayerTickets($player, 1);
        $ticketSupply = $this->game->getGameStateValue(TICKET_SUPPLY);
        $this->game->setGameStateValue(TICKET_SUPPLY, $ticketSupply - 1);

        $this->game->notifyAllPlayers(
            "gainTicket",
            clienttranslate(
                '${player_name} earns 1 ticket from printing ${comicName}'
            ),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "comicName" => $this->game->formatNotificationString(
                    $comic->getName(),
                    $comic->getGenreId()
                ),
            ]
        );
    }
}
