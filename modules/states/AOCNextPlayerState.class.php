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
 * Backend functions used by the nextPlayer State
 *
 * This state handles transitions between players turns during the action phase.
 *
 * @EvanPulgino
 */

class AOCNextPlayerState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the NextPlayer state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * This is called upon entering the nextPlayer state.
     * It increments the number of turns taken and checks if the action phase should end.
     * If the phase should not end, it activates the next player.
     *
     * @return void
     */
    public function stNextPlayer() {
        // Get and increment number of turns taken by 1 (used for calculating game progression)
        $turnsTaken = $this->game->getGameStateValue(TURNS_TAKEN);
        $this->game->setGameStateValue(TURNS_TAKEN, $turnsTaken + 1);

        // If all editors have been placed, end the action phase
        if ($this->game->editorManager->getAllRemainingEditorsCount() < 1) {
            $this->game->gamestate->nextState("endActionsPhase");
            return;
        }

        // Otherwise, activate the next player in turn order
        $this->game->playerManager->activateNextPlayer();
        $newActivePlayer = $this->game->playerManager->getActivePlayer();

        // If the new active player has no editors remaining, skip their turn
        if (
            $this->game->editorManager->getPlayerRemainingEditorsCount(
                $newActivePlayer->getId()
            ) < 1
        ) {
            $this->game->gamestate->nextState("skipPlayer");
            return;
        }

        // Otherwise, start the new active player's turn
        $this->game->gamestate->nextState("nextPlayerTurn");
    }
}
