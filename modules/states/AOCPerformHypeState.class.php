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
 * Backend functions used by the performHype State
 *
 * During this state, the player may choose a comic in their hand to hype
 *
 * @EvanPulgino
 */

class AOCPerformHypeState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the PerformHype state
     *
     * Args:
     *
     * @return array The list of args used by the PerformHype state
     */
    public function getArgs($playerId = null) {
        return [];
    }
}
