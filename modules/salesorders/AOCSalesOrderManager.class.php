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
 * AOCSalesOrderManager.class.php
 *
 * Sales order manager
 *
 */

class AOCSalesOrderManager extends APP_GameClass {
    private $game;

    private $salesOrdersForPlayerCount = [
        2 => [3, 3, 4, 5, 6],
        3 => [3, 3, 4, 4, 5, 6],
        4 => [3, 3, 3, 4, 4, 5, 6],
    ];

    public function __construct($game) {
        $this->game = $game;
    }

    public function setupNewGame($playerCount) {
        $this->createSalesOrderTiles($playerCount);
        $this->distributeSalesOrderTiles($playerCount);
    }

    public function getSalesOrders() {
        $sql =
            "SELECT sales_order_id id, sales_order_genre genre, sales_order_value value, sales_order_fans fans, sales_order_owner playerId, sales_order_location location, sales_order_flipped flipped FROM sales_order";
        $rows = self::getCollectionFromDb($sql);
        $salesOrders = [];
        foreach ($rows as $row) {
            $salesOrders[] = new AOCSalesOrder($row);
        }
        return $salesOrders;
    }

    public function getSalesOrdersUiData() {
        $salesOrders = $this->getSalesOrders();
        $uiData = [];
        foreach ($salesOrders as $salesOrder) {
            $uiData[] = $salesOrder->getUiData();
        }
        return $uiData;
    }

    private function createSalesOrderTiles($playerCount) {
        $salesOrderValues = $this->salesOrdersForPlayerCount[$playerCount];
        foreach (GENRE_KEYS as $genre) {
            foreach ($salesOrderValues as $value) {
                $sql = "INSERT INTO sales_order (sales_order_genre, sales_order_value, sales_order_fans, sales_order_location) VALUES ($genre, $value, $value-2, 2)";
                $this->game->DbQuery($sql);
            }
        }
    }

    private function distributeSalesOrderTiles($playerCount) {
        $salesOrderTiles = $this->getSalesOrders();
        $salesOrderSpaces = SALES_ORDER_SPACES[$playerCount];
        $placedSalesOrders = [];

        // Shuffle the sales order tiles and place on the board
        shuffle($salesOrderTiles);
        foreach ($salesOrderSpaces as $space) {
            $placedSalesOrders[$space] = array_pop($salesOrderTiles);
        }

        // Validate that no connection has 3 or more of 1 genre
        $salesOrderConnections = SALES_ORDER_CONNECTIONS[$playerCount];
        foreach ($salesOrderConnections as $connection) {
            $genres = [];
            foreach ($connection as $space) {
                $genres[] = $placedSalesOrders[$space]->getGenreId();
            }

            $genreCounts = array_count_values($genres);
            foreach ($genreCounts as $genreCount) {
                if ($genreCount >= 3) {
                    $this->distributeSalesOrderTiles($playerCount);
                    break;
                }
            }
        }

        // If valid, save the sales order tiles to the database
        foreach ($placedSalesOrders as $space => $salesOrder) {
            $sql =
                "UPDATE sales_order SET sales_order_location = $space WHERE sales_order_id = " .
                $salesOrder->getId();
            $this->game->DbQuery($sql);
        }
    }
}
