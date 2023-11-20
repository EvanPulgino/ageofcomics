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
    function getArgs() {
        $queryParams = [
            "card_owner" => $this->game->getActivePlayerId(),
            "NOT card_location" => LOCATION_PLAYER_MAT,
        ];
        $cardsInHand = $this->game->cardManager->findCards($queryParams);

        return [
            "numberToDiscard" => count($cardsInHand) - 6,
        ];
    }

    /**
     * Discards the cards the player has selected, then moves to the next player's turn
     *
     * @param int[] $cardsToDiscard The list of card IDs the player has selected to discard
     */
    function confirmDiscard($cardsToDiscard) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);

        // Iterate through the cards to discard, and discard them
        foreach ($cardsToDiscard as $cardId) {
            // Get card object, set location to discard and player to none, then save
            $cardToDiscard = $this->game->cardManager->getCard($cardId);
            $cardToDiscard->setLocation(LOCATION_DISCARD);
            $cardToDiscard->setLocationArg(0);
            $cardToDiscard->setPlayerId(0);
            $this->game->cardManager->saveCard($cardToDiscard);

            // Get card string to display in notification
            $cardText = "";
            switch ($cardToDiscard->getTypeId()) {
                case CARD_TYPE_ARTIST:
                    $cardText = "an artist";
                    break;
                case CARD_TYPE_WRITER:
                    $cardText = "a writer";
                    break;
                case CARD_TYPE_COMIC:
                    $cardText = $cardToDiscard->getName();
                    break;
            }

            // Notify all players that the card has been discarded
            $this->game->notifyAllPlayers(
                "discardCard",
                clienttranslate('${player_name} discards ${cardText}'),
                [
                    "player" => $activePlayer->getUiData(),
                    "player_name" => $activePlayer->getName(),
                    "card" => $cardToDiscard->getUiData($activePlayerId),
                    "cardText" => $cardText,
                ]
            );
        }

        // Move to the next player's turn
        $this->game->gamestate->nextState("nextPlayerTurn");
    }
}
