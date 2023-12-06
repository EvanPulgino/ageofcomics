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
 * Backend functions used by the performPrint State
 *
 * @EvanPulgino
 */

class AOCPerformPrintState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the PerformPrint state
     *
     * Args:
     * - selectedActionSpace => The id of the action space where the player placed their editor
     *
     * @return array The list of args used by the PerformPrint state
     */
    public function getArgs() {
        $activePlayer = $this->game->playerManager->getActivePlayer();
        return [
            "artists" => $this->getArtists($activePlayer),
            "money" => $activePlayer->getMoney(),
            "printableComics" => $this->getPrintableComics($activePlayer),
            "selectedActionSpace" => $this->game->getGameStateValue(
                SELECTED_ACTION_SPACE
            ),
            "writers" => $this->getWriters($activePlayer),
        ];
    }

    /**
     * Perform the print action
     *
     * @param int $comicId The id of the comic to print
     * @param int $artistId The id of the artist to use
     * @param int $writerId The id of the writer to use
     */
    public function printComic($comicId, $artistId, $writerId) {
        // Get the active player
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Get the cards used
        $comicCard = $this->game->cardManager->getCard($comicId);
        $artistCard = $this->game->cardManager->getCard($artistId);
        $writerCard = $this->game->cardManager->getCard($writerId);

        // Get the number of cards on player mat
        $cardsOnMat = $this->game->cardManager->getCards(
            null,
            null,
            LOCATION_PLAYER_MAT,
            $activePlayer->getId()
        );

        // Get the number of comics printed (3 cards per comic)
        $numberComicsPrinted = $cardsOnMat ? count($cardsOnMat) / 3 : 0;

        // Move the writer to the player board and pay them
        $this->assignCreative(
            $activePlayer,
            $writerCard,
            CARD_TYPE_WRITER,
            $numberComicsPrinted + 1
        );

        // Move the artist to the player board and pay them
        $this->assignCreative(
            $activePlayer,
            $artistCard,
            CARD_TYPE_ARTIST,
            $numberComicsPrinted + 1
        );

        // Move the comic to the player board and spend the ideas
        $this->assignComic($activePlayer, $comicCard, $numberComicsPrinted + 1);

        // Get card bonus

        // Set initial fans

        // Add mini comic to chart

        // Check for mastery token gain

        // Check for upgrade cube unlock

        // Check for double print

        $this->game->gamestate->nextState("nextPlayerTurn");
    }

    /**
     * Assign a comic card to a player mat slot + spend ideas if original
     *
     * @param AOCPlayer $player The player to assign the comic to
     * @param AOCComicCard|AOCRipoffCard $card The comic card to assign
     * @param int $cardSlot The slot on the player mat to assign the comic to
     */
    private function assignComic($player, $card, $cardSlot) {
        $card->setPlayerId($player->getId());
        $card->setLocation(LOCATION_PLAYER_MAT);
        $card->setLocationArg($cardSlot);
        $this->game->cardManager->saveCard($card);

        $spentIdeas = $this->spendIdeas($player, $card);
        $genreString = $this->game->formatNotificationString(
            $card->getGenre(),
            $card->getGenreId()
        );
        $spentIdeasString =
            $spentIdeas > 0 ? " by spending 2 " . $genreString . " ideas" : "";

        $this->game->notifyAllPlayers(
            "assignComic",
            clienttranslate(
                '${player_name} prints ${comicName}${spendingIdeas}'
            ),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "comicName" => $this->game->formatNotificationString(
                    $card->getName(),
                    $card->getGenreId()
                ),
                "spendingIdeas" => $spentIdeasString,
                "card" => $card->getUiData(0),
                "slot" => $cardSlot,
                "spentIdeas" => $spentIdeas,
            ]
        );
    }

    /**
     * Assign a creative card to a player mat slot + pay their cost
     *
     * @param AOCPlayer $player The player to assign the creative to
     * @param AOCArtistCard|AOCWriterCard $card The creative card to assign
     * @param int $cardType The type of creative card to assign
     * @param int $cardSlot The slot on the player mat to assign the creative to
     */
    private function assignCreative($player, $card, $cardType, $cardSlot) {
        $card->setPlayerId($player->getId());
        $card->setLocation(LOCATION_PLAYER_MAT);
        $card->setLocationArg($cardSlot);
        $this->game->cardManager->saveCard($card);

        $this->game->playerManager->adjustPlayerMoney(
            $player,
            -$card->getValue()
        );

        $this->game->notifyAllPlayers(
            "assignCreative",
            clienttranslate(
                '${player_name} pays ${cost} to use a value-${cost} ${creativeTypeText}'
            ),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "cost" => $card->getValue(),
                "creativeTypeText" => $this->game->formatNotificationString(
                    $cardType == CARD_TYPE_ARTIST
                        ? clienttranslate("artist")
                        : clienttranslate("writer"),
                    $card->getGenreId()
                ),
                "card" => $card->getUiData(0),
                "slot" => $cardSlot,
            ]
        );
    }

    /**
     * Get a list of artists from player's hand
     *
     * @param AOCPlayer $player The player to check
     * @return array The list of artists uiData in the player's hand
     */
    private function getArtists($player) {
        $artists = $this->game->cardManager->getCards(
            CARD_TYPE_ARTIST,
            null,
            LOCATION_HAND,
            $player->getId()
        );
        $artists = array_map(function ($artist) {
            return $artist->getUiData(0);
        }, $artists);
        return $artists;
    }

    /**
     * Get a list of comics the player can print
     *
     * @param AOCPlayer $player The player to check
     * @return array The list of comics uiData the player can print
     */
    private function getPrintableComics($player) {
        $printableComics = [];

        // Get the comics in the player's hand
        $comicsInHand = $this->game->cardManager->getCards(
            CARD_TYPE_COMIC,
            null,
            LOCATION_HAND,
            $player->getId()
        );

        foreach ($comicsInHand as $comic) {
            // If the player has at least 2 ideas in the comic's genre, they can print it
            if ($player->getIdeas($comic->getGenreId()) >= 2) {
                $printableComics[] = $comic->getUiData(0);
            }
        }

        // TODO: Check Ripoffs

        return $printableComics;
    }

    /**
     * Get a list of writers from player's hand
     *
     * @param AOCPlayer $player The player to check
     * @return array The list of writers uiData in the player's hand
     */
    private function getWriters($player) {
        $writers = $this->game->cardManager->getCards(
            CARD_TYPE_WRITER,
            null,
            LOCATION_HAND,
            $player->getId()
        );
        $writers = array_map(function ($writer) {
            return $writer->getUiData(0);
        }, $writers);
        return $writers;
    }

    /**
     * Spend ideas if the comic is original
     *
     * @param AOCPlayer $player The player to spend ideas for
     * @param AOCComicCard $comic The comic to check
     * @return int The number of ideas spent
     */
    private function spendIdeas($player, $comic) {
        // If comic is original, spend 2 ideas of its genre
        if ($comic->getTypeId() == CARD_TYPE_COMIC) {
            $genreId = $comic->getGenreId();
            $this->game->playerManager->adjustPlayerIdeas(
                $player,
                $genreId,
                -2
            );
            return 2;
        }
        return 0;
    }
}
