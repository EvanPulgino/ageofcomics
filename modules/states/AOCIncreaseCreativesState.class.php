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
 * This state handles backend actions for the increase creatives state.
 *
 * @EvanPulgino
 */

class AOCIncreaseCreativesState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the IncreaseCreatives state
     *
     * Args:
     * - None
     */
    public function getArgs($playerId) {
        $currentPlayer = $this->game->playerManager->getPlayer($playerId);
        $cardsOnPlayerMat = $this->game->cardManager->getCardsUiData(
            $playerId,
            null,
            null,
            LOCATION_PLAYER_MAT,
            $playerId
        );

        return [
            "cardsOnPlayerMat" => $cardsOnPlayerMat,
            "currentPlayer" => $currentPlayer->getUiData(),
        ];
    }
}
