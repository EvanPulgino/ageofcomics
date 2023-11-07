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
 * Sales order manager, handles all logic for sales order tiles
 *
 * @EvanPulgino
 */

class AOCSalesOrderManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Setups sales order tiles for a new game
     *
     * @param int $playerCount The number of players in the game
     * @return void
     */
    public function setupNewGame($playerCount) {
        $this->createSalesOrderTiles($playerCount);
        $this->distributeSalesOrderTiles($playerCount);
    }

    /**
     * Flip all sales orders of a given genre on the map
     *
     * @param int $genre The id of the genre to flip
     * @return array An array of the just flipped sales order tiles formatted for the UI
     */
    public function flipSalesOrdersOnMap($genre) {
        $sql = "UPDATE sales_order SET sales_order_flipped = 1 WHERE sales_order_genre = $genre AND sales_order_location = 9";
        self::DbQuery($sql);
        return $this->getFlippedSalesOrdersUiData($genre);
    }

    /**
     * Get all flipped sales order tiles of a given genre formatted for the UI
     *
     * @param int $genre The id of the genre to get flipped sales orders for
     * @return array An array of flipped sales order tiles formatted for the UI
     */
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

    /**
     * Get all sales order tiles
     *
     * @return AOCSalesOrder[]
     */
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

    /**
     * Get all sales order tiles formatted for the UI
     *
     * @return array An array of sales order tiles formatted for the UI
     */
    public function getSalesOrdersUiData() {
        $salesOrders = $this->getSalesOrders();
        $uiData = [];
        foreach ($salesOrders as $salesOrder) {
            $uiData[] = $salesOrder->getUiData();
        }
        return $uiData;
    }

    /**
     * Create sales order tiles for a new game based on the number of players
     *
     * @param int $playerCount The number of players in the game
     * @return void
     */
    private function createSalesOrderTiles($playerCount) {
        $salesOrderValues = SALES_ORDERS_FOR_PLAYER_COUNT[$playerCount];
        foreach (GENRE_KEYS as $genre) {
            foreach ($salesOrderValues as $value) {
                $sql = "INSERT INTO sales_order (sales_order_genre, sales_order_value, sales_order_fans, sales_order_location) VALUES ($genre, $value, $value-2, 9)";
                $this->game->DbQuery($sql);
            }
        }
    }

    /**
     * Distribute sales order tiles to the board based on the number of players
     *
     * @param int $playerCount The number of players in the game
     * @return void
     */
    private function distributeSalesOrderTiles($playerCount) {
        $salesOrders = $this->getSalesOrders();
        $placedSalesOrders = $this->shuffleSalesOrders(
            $playerCount,
            $salesOrders
        );
        $this->savePlacedSalesOrders($placedSalesOrders);
    }

    /**
     * Save placed sales order tiles to the database
     *
     * @param AOCSalesOrder[] $shuffledOrders The shuffled and placed sales order tiles
     * @return void
     */
    private function savePlacedSalesOrders($shuffledOrders) {
        foreach ($shuffledOrders as $space => $salesOrder) {
            $sql =
                "UPDATE sales_order SET sales_order_location_arg = $space WHERE sales_order_id = " .
                $salesOrder->getId();
            $this->game->DbQuery($sql);
        }
    }

    /**
     * Shuffle sales order tiles and place on the board
     *
     * There cannot be 3 or more adjacent sales orders of the same genre.
     * Recursively shuffle and place sales orders until this condition is met.
     *
     * @param int $playerCount The number of players in the game
     * @param AOCSalesOrder[] $salesOrders The sales order tiles to shuffle and place
     * @return AOCSalesOrder[] The shuffled and placed sales order tiles
     */
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

        // If the sales order tiles are not validly placed, recursively shuffle and place again
        if ($restart) {
            return $this->shuffleSalesOrders($playerCount, $salesOrders);
        } else {
            return $placedSalesOrders;
        }
    }
}
