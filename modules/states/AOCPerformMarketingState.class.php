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
 * Backend functions used by the performMarketing State
 *
 * During this state, the player may spend money to gain fains for comics
 *
 * @EvanPulgino
 */

class AOCPerformMarketingState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the PerformMarketing state
     *
     * Args:
     *
     * @return array The list of args used by the PerformMarketing state
     */
    public function getArgs($playerId = null) {
        return [];
    }
}
