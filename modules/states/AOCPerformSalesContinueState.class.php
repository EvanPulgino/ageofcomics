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
 * Backend functions used by the continueSales State
 *
 * This state handles transitions between consecutive perform sales states.
 *
 * @EvanPulgino
 */

class AOCPerformSalesContinueState {
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
    public function getArgs($playerId = null) {
        return [];
    }

    /**
     * This is called after doing an action during the perform sales state that does not end the state.
     *
     * @return void
     */
    public function stPerformSalesContinue() {
        // Re-enter perform sales state
        $this->game->gamestate->nextState("continueSales");
    }
}
