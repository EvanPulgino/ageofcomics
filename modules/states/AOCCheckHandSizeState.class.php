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
     *  - numberToDiscard => The number of cards the player must discard
     *
     * @return array The list of args used by the CheckHandSize state
     */
    function getArgs() {
        return [
            "numberToDiscard" =>
                count(
                    $this->game->cardManager->getPlayerHand($this->game->getActivePlayerId())
                ) - 6,
        ];
    }

    function confirmDiscard($args) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);
        $cardsToDiscard = explode(",", $args[0]);

        foreach ($cardsToDiscard as $cardId) {
            $discardedCard = $this->game->cardManager->discardCard($cardId);
            $this->game->notifyAllPlayers(
                "discardCard",
                clienttranslate('${player_name} discards ${type_singular}'),
                [
                    "player" => $activePlayer->getUiData(),
                    "player_name" => $activePlayer->getName(),
                    "card" => $discardedCard->getUiData($activePlayerId),
                    "type_singular" =>
                        $discardedCard->getTypeId() == 1
                            ? "an artist"
                            : "a writer",
                ]
            );
        }

        $this->game->gamestate->nextState("nextPlayerTurn");
    }
}
