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
        $actionSpace = $this->game->getGameStateValue(SELECTED_ACTION_SPACE);
        if ($actionSpace == 40001) {
            $this->game->setGameStateValue(SELECTED_ACTION_SPACE, 0);
            $this->game->gamestate->nextState("doublePrint");
            return;
        }

        $this->game->gamestate->nextState("nextPlayerTurn");
    }
}
