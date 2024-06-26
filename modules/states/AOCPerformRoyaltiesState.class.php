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
 * Backend functions used by the performRoyalties State
 *
 * During this state, the player will receive royalties.
 *
 * @EvanPulgino
 */

class AOCPerformRoyaltiesState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the PerformRoyalties state
     *
     * Args:
     * - selectedActionSpace => The id of the action space where the player placed their editor
     *
     * @return array The list of args used by the PerformRoyalties state
     */
    public function getArgs($playerId = null) {
        return [
            "selectedActionSpace" => $this->game->getGameStateValue(
                SELECTED_ACTION_SPACE
            ),
        ];
    }

    /**
     * Performs the royalties action
     *
     * @return void
     */
    public function stGainRoyalties() {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Get the action space the player selected
        $actionSpace = $this->game->getGameStateValue(SELECTED_ACTION_SPACE);

        // Get the amount of royalties the player will receive based on the action space
        $amount = ROYALTIES_AMOUNTS[$actionSpace];

        // Give the player the royalties
        $this->game->playerManager->adjustPlayerMoney($activePlayer, $amount);

        // Notify the players
        $this->game->notifyAllPlayers(
            "takeRoyalties",
            clienttranslate('${player_name} takes royalties of $${amount}'),
            [
                "player" => $activePlayer->getUiData(),
                "player_name" => $activePlayer->getName(),
                "amount" => $amount,
            ]
        );

        // Go to next player's turn
        $this->game->gamestate->nextState("nextPlayerTurn");
    }
}
