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
 * Backend functions used by the roundEndRemoveEditors State
 *
 * This state handles the remove editors fans step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndRemoveEditorsState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndRemoveEditors state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * Remove all editors from the board and return them to their owners
     *
     * @return void
     */
    public function stRoundEndRemoveEditors() {
        // Do some stuff here
        $this->game->gamestate->nextState("refillCards");
    }
}
