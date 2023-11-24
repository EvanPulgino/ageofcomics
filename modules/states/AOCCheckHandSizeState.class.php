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
 * Backend functions used by the checkHandSize State
 *
 * This state is entered if a player has more than 6 cards in their hand (including hyped comics) after any action that gains cards (i.e. Hire, Develop)
 *
 * @EvanPulgino
 */

class AOCCheckHandSizeState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the CheckHandSize state
     *
     * Args:
     *  - numberToDiscard => The number of cards the player must discard (Number of cards in hand + hyped comics - 6)
     *
     * @return array The list of args used by the CheckHandSize state
     */
    public function getArgs() {
        $cardsInHand = $this->game->cardManager->getCountForHandSizeCheck(
            $this->game->getActivePlayerId()
        );

        return [
            "numberToDiscard" => $cardsInHand - 6,
        ];
    }

    /**
     * Discards the cards the player has selected, then moves to the next player's turn
     *
     * @param int[] $cardsToDiscard The list of card IDs the player has selected to discard
     */
    public function confirmDiscard($cardsToDiscard) {
        // Get the active player
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Iterate through the cards to discard, and discard them
        foreach ($cardsToDiscard as $cardId) {
            // Discard the card
            $discardedCard = $this->game->cardManager->discardCard($cardId);

            // Get card string to display in notification
            $cardText = "";
            switch ($discardedCard->getTypeId()) {
                case CARD_TYPE_ARTIST:
                    $cardText = "an artist";
                    break;
                case CARD_TYPE_WRITER:
                    $cardText = "a writer";
                    break;
                case CARD_TYPE_COMIC:
                    $cardText = $discardedCard->getName();
                    break;
            }

            // Notify all players that the card has been discarded
            $this->game->notifyAllPlayers(
                "discardCard",
                clienttranslate('${player_name} discards ${cardText}'),
                [
                    "player" => $activePlayer->getUiData(),
                    "player_name" => $activePlayer->getName(),
                    "card" => $discardedCard->getUiData($activePlayer->getId()),
                    "cardText" => $cardText,
                ]
            );
        }

        // Move to the next player's turn
        $this->game->gamestate->nextState("nextPlayerTurn");
    }
}
