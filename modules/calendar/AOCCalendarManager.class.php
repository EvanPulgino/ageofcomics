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
        $calendarGenres = $this->game->genres;
        shuffle($calendarGenres);

        foreach ($calendarPositions as $position) {
            $genre = array_shift($calendarGenres);
            $round = floor($position / 10);
            $sql = "INSERT INTO calendar_tile (calendar_tile_genre, calendar_tile_round, calendar_tile_position, calendar_tile_class) VALUES ($genre, $round, $position, 'calendar-tile-facedown')";
            self::DbQuery($sql);
        }
    }

    /**
     * Flip a calendar tile
     * @param AOCCalendarTile $tile The tile to flip
     * @return void
     */
    public function flipCalendarTile(AOCCalendarTile $tile) {
        $cssClass = $this->getCssClassByGenre($tile->getGenreId());
        $sql = "UPDATE calendar_tile SET calendar_tile_class = '$cssClass' WHERE calendar_tile_id = {$tile->getId()}";
        self::DbQuery($sql);
    }

    /**
     * Get all calendar tiles
     * @return array An array of calendar tiles
     */
    public function getCalendarTiles() {
        $sql =
            "SELECT calendar_tile_id id, calendar_tile_genre genre, calendar_tile_round round, calendar_tile_position position, calendar_tile_class class FROM calendar_tile";
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

    /**
     * Get a calendar tiles css value by its genre
     * @param int $genre The id of the genre
     * @return string The calendar tile's css class
     */
    public function getCssClassByGenre($genre) {
        switch ($genre) {
            case GENRE_CRIME:
                return "calendar-tile-crime";
            case GENRE_HORROR:
                return "calendar-tile-horror";
            case GENRE_ROMANCE:
                return "calendar-tile-romance";
            case GENRE_SCIFI:
                return "calendar-tile-scifi";
            case GENRE_SUPERHERO:
                return "calendar-tile-superhero";
            case GENRE_WESTERN:
                return "calendar-tile-western";
            default:
                return "calendar-tile-facedown";
        }
    }
}
