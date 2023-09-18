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
            "SELECT sales_order_id id, sales_order_genre genre, sales_order_value value, sales_order_fans fans, sales_order_owner playerId, sales_order_location location, sales_order_location_arg locationArg, sales_order_flipped flipped FROM sales_order";
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

    public function flipSalesOrdersOnMap($genre) {
        $sql = "UPDATE sales_order SET sales_order_flipped = 1 WHERE sales_order_genre = $genre AND sales_order_location = 9";
        self::DbQuery($sql);
        return $this->getFlippedSalesOrdersUiData($genre);
    }

    public function getFlippedSalesOrdersUiData($genre) {
        $sql = "SELECT sales_order_id id, sales_order_genre genre, sales_order_value value, sales_order_fans fans, sales_order_owner playerId, sales_order_location location, sales_order_location_arg locationArg, sales_order_flipped flipped FROM sales_order WHERE sales_order_genre = $genre AND sales_order_location = 9";
        $rows = self::getCollectionFromDb($sql);
        $salesOrders = [];
        foreach ($rows as $row) {
            $salesOrders[] = new AOCSalesOrder($row);
        }
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
                $sql = "INSERT INTO sales_order (sales_order_genre, sales_order_value, sales_order_fans, sales_order_location) VALUES ($genre, $value, $value-2, 9)";
                $this->game->DbQuery($sql);
            }
        }
    }

    private function distributeSalesOrderTiles($playerCount) {
        $salesOrders = $this->getSalesOrders();
        $placedSalesOrders = $this->shuffleSalesOrders(
            $playerCount,
            $salesOrders
        );
        $this->savePlacedSalesOrders($placedSalesOrders);
    }

    private function shuffleSalesOrders($playerCount, $salesOrders) {
        $copyOfSalesOrders = $salesOrders;
        $salesOrderSpaces = SALES_ORDER_SPACES[$playerCount];
        $placedSalesOrders = [];
        $restart = false;

        // Shuffle the sales order tiles and place on the board
        shuffle($copyOfSalesOrders);
        foreach ($salesOrderSpaces as $space) {
            $placedSalesOrders[$space] = array_pop($copyOfSalesOrders);
        }

        // Validate that no connection has 3 or more of 1 genre
        $salesOrderConnections = SALES_ORDER_CONNECTIONS[$playerCount];
        foreach ($salesOrderConnections as $connection) {
            $genres = [];
            foreach ($connection as $space) {
                $genres[] = $placedSalesOrders[$space]->getGenreId();
            }

            $genreCounts = array_count_values($genres);

            $restart = false;
            foreach ($genreCounts as $genreCount) {
                if ($genreCount >= 3) {
                    $restart = true;
                    break;
                }
            }
            if ($restart) {
                break;
            }
        }

        if ($restart) {
            return $this->shuffleSalesOrders($playerCount, $salesOrders);
        } else {
            return $placedSalesOrders;
        }
    }

    private function savePlacedSalesOrders($shuffledOrders) {
        foreach ($shuffledOrders as $space => $salesOrder) {
            $sql =
                "UPDATE sales_order SET sales_order_location_arg = $space WHERE sales_order_id = " .
                $salesOrder->getId();
            $this->game->DbQuery($sql);
        }
    }
}
