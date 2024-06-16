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
 * Backend functions used by the roundEndRefillCards State
 *
 * This state handles the refill cards fans step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndRefillCardsState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndRefillCards state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * Remove all cards in the market and refill it
     *
     * @return void
     */
    public function stRoundEndRefillCards() {
        // Do some stuff here
        $this->game->gamestate->nextState("startNewRound");
    }
}
