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
 * Backend functions used by the performPrint State
 *
 * @EvanPulgino
 */

class AOCPerformPrintState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the PerformPrint state
     *
     * Args:
     * - selectedActionSpace => The id of the action space where the player placed their editor
     *
     * @return array The list of args used by the PerformPrint state
     */
    public function getArgs() {
        return [
            "selectedActionSpace" =>
                $this->game->getGameStateValue(SELECTED_ACTION_SPACE),
        ];
    }
}
