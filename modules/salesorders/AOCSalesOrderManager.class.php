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

require_once "AOCSalesOrder.class.php";
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
    }

    public function getSalesOrders() {
        $sql =
            "SELECT sales_order_id id, sales_order_genre genre, sales_order_value value, sales_order_fans fans, sales_order_owner playerId, sales_order_location location, sales_order_class class FROM sales_order";
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
        $salesOrderGenres = $this->game->genres;
        $salesOrderValues = $this->salesOrdersForPlayerCount[$playerCount];
        foreach ($salesOrderGenres as $genre) {
            foreach ($salesOrderValues as $value) {
                $sql = "INSERT INTO sales_order (sales_order_genre, sales_order_value, sales_order_fans, sales_order_location, sales_order_class) VALUES ($genre, $value, $value-2, 2, 'salesorder_facedown_$genre')";
                $this->game->DbQuery($sql);
            }
        }
    }
}
