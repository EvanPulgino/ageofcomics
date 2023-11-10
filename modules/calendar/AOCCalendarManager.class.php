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
 * Calendar tile manager, handles all calendar tile related logic
 *
 * @EvanPulgino
 */
class AOCCalendarManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Setup calendar tiles for a new game
     *
     * @return void
     */
    public function setupNewGame() {
        $calendarPositions = [10, 20, 31, 32, 40, 50];
        $calendarGenres = GENRE_KEYS;
        shuffle($calendarGenres);

        foreach ($calendarPositions as $position) {
            $genre = array_shift($calendarGenres);
            $round = floor($position / 10);
            $sql = "INSERT INTO calendar_tile (calendar_tile_genre, calendar_tile_round, calendar_tile_position) VALUES ($genre, $round, $position)";
            self::DbQuery($sql);
        }
    }

    /**
     * Flip a calendar tile
     *
     * @param AOCCalendarTile $tile The tile to flip
     * @return void
     */
    public function flipCalendarTile(AOCCalendarTile $tile) {
        $sql = "UPDATE calendar_tile SET calendar_tile_flipped = 1 WHERE calendar_tile_id = {$tile->getId()}";
        self::DbQuery($sql);
    }

    /**
     * Flip all calendar tiles for a round
     *
     * @param int $round The round to flip tiles for
     * @return array An array of calendar tiles
     */
    public function flipCalendarTilesByRound($round) {
        $sql = "UPDATE calendar_tile SET calendar_tile_flipped = 1 WHERE calendar_tile_round = $round";
        self::DbQuery($sql);
        return $this->getCalendarTilesByRound($round);
    }

    /**
     * Get all calendar tiles
     *
     * @return AOCCalendarTile[] An array of calendar tiles
     */
    public function getCalendarTiles() {
        $sql =
            "SELECT calendar_tile_id id, calendar_tile_genre genre, calendar_tile_round round, calendar_tile_position position, calendar_tile_flipped flipped FROM calendar_tile";
        $rows = self::getObjectListFromDB($sql);

        $tiles = [];
        foreach ($rows as $row) {
            $tiles[] = new AOCCalendarTile($row);
        }
        return $tiles;
    }

    /**
     * Get all calendar tiles for a round
     *
     * @param int $round The round to get tiles for
     * @return AOCCalendarTile[] An array of calendar tiles
     */
    private function getCalendarTilesByRound($round) {
        $sql = "SELECT calendar_tile_id id, calendar_tile_genre genre, calendar_tile_round round, calendar_tile_position position, calendar_tile_flipped flipped FROM calendar_tile WHERE calendar_tile_round = $round";
        $rows = self::getObjectListFromDB($sql);

        $tiles = [];
        foreach ($rows as $row) {
            $tiles[] = new AOCCalendarTile($row);
        }
        return $tiles;
    }

    /**
     * Get all calendar tiles in UI data format
     *
     * @return array An array of calendar tiles in UI data format
     */
    public function getCalendarTilesUiData() {
        $tiles = $this->getCalendarTiles();
        $uiData = [];
        foreach ($tiles as $tile) {
            $uiData[] = $tile->getUiData();
        }
        return $uiData;
    }
}
