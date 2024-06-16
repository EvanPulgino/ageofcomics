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
 * Backend functions used by the roundEndEstablishPlayerOrder State
 *
 * This state handles the establish player order step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndEstablishPlayerOrderState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndEstablishPOlayerOrder state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * Establish player order for the next round
     *
     * @return void
     */
    public function stRoundEndEstablishPlayerOrder() {
        // Do some stuff here
        $this->game->gamestate->nextState("subtractFans");
    }
}
