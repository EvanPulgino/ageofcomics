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
 * Backend functions used by the startNewRound State
 *
 * @EvanPulgino
 */

class AOCStartNewRoundState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    function getArgs() { return []; }

    function stStartNewRound() {
        // 1. Increment round number
        $currentRound = $this->game->getGameStateValue(CURRENT_ROUND) + 1;
        $this->game->setGameStateValue(CURRENT_ROUND, $currentRound);

        // 2. Flip Calendar Tile(s)
        $flippedTiles = $this->game->calendarManager->flipCalendarTilesByRound(
            $currentRound
        );
        $flippedTilesUiData = array_map(function ($tile) {
            return $tile->getUiData();
        }, $flippedTiles);

        $this->game->notifyAllPlayers(
            "flipCalendarTiles",
            clienttranslate('Flipping calendar tiles for round ${round}'),
            [
                "round" => $currentRound,
                "flippedTiles" => $flippedTilesUiData,
            ]
        );

        // 3. Flip Sales Orders on Map by Genre
        foreach ($flippedTiles as $tile) {
            $flippedSalesOrdersUiData = $this->game->salesOrderManager->flipSalesOrdersOnMap(
                $tile->getGenreId()
            );
            $this->game->notifyAllPlayers(
                "flipSalesOrders",
                clienttranslate('Flipping ${genre} sales orders on map'),
                [
                    "genre" => $tile->getGenre(),
                    "flippedSalesOrders" => $flippedSalesOrdersUiData,
                ]
            );
        }

        // 4. Refill Ideas
        // 5. Add Hype

        $this->game->playerManager->activateNextPlayer();
        $this->game->gamestate->nextState("startActionsPhase");
    }

}