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
 * In this state, a new round is started by following these steps:
 *  - 1. Increment round number
 *  - 2. Flip Calendar Tile(s)
 *  - 3. Flip Sales Orders on Map based on flipped Calendar Tile(s)
 *  - 4. Refill Ideas on board
 *  - 5. Add hype tokens to hyped comics
 *  - 6. Move to next state:
 *       - on first round, this is the startActionsPhase state
 *       - on all other rounds, this is the increaseCreatives state
 *
 * @EvanPulgino
 */

class AOCStartNewRoundState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    function getArgs() {
        return [];
    }

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

        // Notify players of flipped tiles
        $this->game->notifyAllPlayers(
            "flipCalendarTiles",
            clienttranslate(
                'Flipping calendar ${tileString} for round ${round}'
            ),
            [
                "round" => $currentRound,
                "tileString" =>
                    count($flippedTiles) > 1
                        ? clienttranslate("tiles")
                        : clienttranslate("tile"),
                "flippedTiles" => $flippedTilesUiData,
            ]
        );

        // 3. Flip Sales Orders on Map by Genre
        foreach ($flippedTiles as $tile) {
            $flippedSalesOrdersUiData = $this->game->salesOrderManager->flipSalesOrdersOnMap(
                $tile->getGenreId()
            );
            // Notify players of flipped sales orders
            $this->game->notifyAllPlayers(
                "flipSalesOrders",
                clienttranslate('Flipping all ${genre} sales orders on map'),
                [
                    "genre" => $this->game->formatNotificationString(
                        $tile->getGenre(),
                        $tile->getGenreId()
                    ),
                    "flippedSalesOrders" => $flippedSalesOrdersUiData,
                ]
            );
        }

        // 4. Refill Ideas
        // 5. Add Hype

        $this->game->playerManager->activateFirstPlayer();
        $this->game->gamestate->nextState("startActionsPhase");
    }
}
