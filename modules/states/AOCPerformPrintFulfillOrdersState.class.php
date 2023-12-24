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
 * Backend functions used by the performPrintFulfillOrders State
 *
 * @EvanPulgino
 */

class AOCPerformPrintFulfillOrdersState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    public function getArgs() {
    }

    public function stPerformPrintFulfillOrders() {
        $activePlayer = $this->game->playerManager->getActivePlayer();
        $actionSpace = $this->game->getGameStateValue(SELECTED_ACTION_SPACE);
        if ($actionSpace == 40001 && $this->playerCanPrint($activePlayer)) {
            $this->game->setGameStateValue(SELECTED_ACTION_SPACE, 0);
            $this->game->gamestate->nextState("doublePrint");
            return;
        }

        $this->game->gamestate->nextState("nextPlayerTurn");
    }

    /**
     * Get the creative card with the lowest cost in a player's hand, if any
     *
     * @param AOCPlayer $player The player to get the lowest cost creative card for
     * @param int $creativeType The type of creative card to get the lowest cost for
     * @return AOCArtistCard|AOCWriterCard|null The creative card with the lowest cost in the player's hand or null if the player doesn't have any creative cards
     */
    private function getPlayerLowestCostCreative($player, $creativeType) {
        $creativeCards = $this->game->cardManager->getCards(
            $creativeType,
            null,
            LOCATION_HAND,
            $player->getId(),
            "card_type_arg ASC"
        );

        if (count($creativeCards) > 0) {
            return $creativeCards[0];
        } else {
            return null;
        }
    }

    /**
     * Checks if the player can take the print action
     *
     * @param AOCPlayer $player The player to check if they can take the print action
     * @return bool True if the player can take the print action, false otherwise
     */
    private function playerCanPrint($player) {
        // Get lowest cost artist
        $lowestCostArtist = $this->getPlayerLowestCostCreative(
            $player,
            CARD_TYPE_ARTIST
        );
        // If the player doesn't have an artist, they can't print
        if ($lowestCostArtist == null) {
            return false;
        }

        // Get lowest cost writer
        $lowestCostWriter = $this->getPlayerLowestCostCreative(
            $player,
            CARD_TYPE_WRITER
        );
        // If the player doesn't have a writer, they can't print
        if ($lowestCostWriter == null) {
            return false;
        }

        // Get the lowest possible cost to print a comic
        $lowestPossibleComicCost =
            $lowestCostArtist->getValue() + $lowestCostWriter->getValue();
        // If the player doesn't have enough money to print a comic, they can't print
        if ($lowestPossibleComicCost > $player->getMoney()) {
            return false;
        }

        // Get the comics in the player's hand
        $comicsInHand = $this->game->cardManager->getCards(
            CARD_TYPE_COMIC,
            null,
            LOCATION_HAND,
            $player->getId()
        );
        // If the player has at least one comic in their hand, they can print
        if (count($comicsInHand) > 0) {
            foreach ($comicsInHand as $comic) {
                // If the player has 2 matching ideas they can print
                if ($player->getIdeas($comic->getGenreId()) >= 2) {
                    return true;
                }
            }
        }

        $avaliableRipoffs = $this->game->cardManager->getPrintableRipoffsByPlayer(
            $player->getId()
        );

        if (count($avaliableRipoffs) > 0) {
            return true;
        }

        return false;
    }
}
