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
 * @EvanPulgino
 */

class AOCNextPlayerState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    public function getArgs() {
        return [];
    }

    function stNextPlayer() {
        $turnsTaken = $this->game->getGameStateValue(TURNS_TAKEN);
        $this->game->setGameStateValue(TURNS_TAKEN, $turnsTaken + 1);

        if ($this->game->editorManager->getAllRemainingEditorsCount() < 1) {
            $this->game->gamestate->nextState("endActionsPhase");
            return;
        }

        $this->game->playerManager->activateNextPlayer();
        $newActivePlayer = $this->game->playerManager->getActivePlayer();

        if (
            $this->game->editorManager->getPlayerRemainingEditorsCount(
                $newActivePlayer->getId()
            ) < 1
        ) {
            $this->game->gamestate->nextState("skipPlayer");
            return;
        }

        $this->game->gamestate->nextState("nextPlayerTurn");
    }
}
