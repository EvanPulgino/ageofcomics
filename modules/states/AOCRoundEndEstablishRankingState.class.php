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
 * Backend functions used by the roundEndEstablishRanking State
 *
 * This state handles the establish ranking step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndEstablishRankingState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndEstablishRanking state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * Establish ranking of players based on their top performing comic
     *
     * @return void
     */
    public function stRoundEndEstablishRanking() {
        // Do some stuff here
        $this->game->gamestate->nextState("payEarnings");
    }
}
