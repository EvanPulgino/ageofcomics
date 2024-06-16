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
 * Backend functions used by the roundEndSubtractFans State
 *
 * This state handles the subtract fans step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndSubtractFansState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndSubtractFans state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * Subtract one fan (to a minimum of one) from each printed comic
     *
     * @return void
     */
    public function stRoundEndSubtractFans() {
        // Do some stuff here
        $this->game->gamestate->nextState("removeEditors");
    }
}
