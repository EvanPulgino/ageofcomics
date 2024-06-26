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
 * Backend functions used by the increaseCreatives State
 *
 * This state handles backend actions for the increase creatives fulfill orders state.
 *
 * @EvanPulgino
 */

class AOCIncreaseCreativesFulfillOrdersState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the IncreaseCreativesFulfillOrders state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }
}
