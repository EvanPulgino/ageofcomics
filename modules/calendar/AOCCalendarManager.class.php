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
 * AOCCalendarManager.class.php
 *
 * Calendar manager class
 *
 */

require_once "AOCCalendarTile.class.php";
class AOCCalendarManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Setup calendar tiles for a new game
     * @return void
     */
    public function setupNewGame() {
        $calendarPositions = [10, 20, 31, 32, 40, 50];
        $genres = [
            GENRE_CRIME,
            GENRE_HORROR,
            GENRE_ROMANCE,
            GENRE_SCIFI,
            GENRE_SUPERHERO,
            GENRE_WESTERN,
        ];
        shuffle($genres);

        foreach ($calendarPositions as $position) {
            $genre = array_shift($genres);
            $round = floor($position / 10);
            $sql = "INSERT INTO calendar_tile (calendar_tile_genre, calendar_tile_round, calendar_tile_position) VALUES ($genre, $round, $position)";
            self::DbQuery($sql);
        }
    }

    /**
     * Get all calendar tiles
     * @return array An array of calendar tiles
     */
    public function getCalendarTiles() {
        $sql =
            "SELECT calendar_tile_id id, calendar_tile_genre genre, calendar_tile_round round, calendar_tile_position position FROM calendar_tile";
        $rows = self::getObjectListFromDB($sql);

        $tiles = [];
        foreach ($rows as $row) {
            $tiles[] = new AOCCalendarTile($row);
        }
        return $tiles;
    }

    /**
     * Get all calendar tiles in UI data format
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
