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
 * Object class for Calendar tile.
 * Contains:
 * - The database ID of the calendar tile
 * - The genre key of the calendar tile
 * - The genre name of the calendar tile
 * - The round the calendar tile will be flipped
 * - The position of the calendar tile, this only matters for round 3 since it has 2 spaces
 * - Whether the calendar tile has been flipped
 * - The CSS class for the calendar tile
 *
 * Calendar tiles are represented by the "calendar_tile" table in the database.
 *
 * A Calendar tile denotes a genre of comic books that will be flipped in a future round.
 *
 * At the start of a round, a Calendar tile is flipped face up, and all Sales Order tiles of that genre are flipped face up so that their genre, value, and fans are now visible.
 * Two Calendar tiles are flipped in round 3.
 *
 * @EvanPulgino
 */
class AOCCalendarTile {
    /**
     * @var int $id The database ID of the calendar tile
     */
    private $id;

    /**
     * @var int $genreId The genre key of the calendar tile
     * @see GENRE_KEYS
     */
    private int $genreId;

    /**
     * @var string $genre The genre name of the calendar tile
     */
    private string $genre;

    /**
     * @var int $round The round the calendar tile will be flipped
     */
    private int $round;

    /**
     * @var int $position The position of the calendar tile, this only matters for round 3 since it has 2 spaces
     */
    private int $position;

    /**
     * @var int $flipped Whether the calendar tile has been flipped
     */
    private int $flipped;

    /**
     * @var string $cssClass The CSS class for the calendar tile
     */
    private string $cssClass;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->genreId = (int) $row["genre"];
        $this->genre = GENRES[$this->genreId];
        $this->round = (int) $row["round"];
        $this->position = (int) $row["position"];
        $this->flipped = (int) $row["flipped"];
        $this->cssClass = $this->deriveCssClass();
    }

    /**
     * Get the calendar tile's ID
     *
     * @return int The ID
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the calendar tile's genre ID
     *
     * @return int The genre ID
     */
    public function getGenreId() {
        return $this->genreId;
    }

    /**
     * Get the calendar tile's genre name
     *
     * @return string The genre name
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Get the round the calendar tile will be flipped
     *
     * @return int The round
     */
    public function getRound() {
        return $this->round;
    }

    /**
     * Get the position of the calendar tile
     *
     * @return int The position
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Get whether the calendar tile has been flipped
     *
     * @return int Whether the calendar tile has been flipped
     */
    public function isFlipped() {
        return $this->flipped;
    }

    /**
     * Get the CSS class for the calendar tile
     *
     * @return string The CSS class
     */
    public function getCssClass() {
        return $this->cssClass;
    }

    /**
     * Get the data formatted for the UI
     *
     * @return array The data formatted for the UI
     */
    public function getUiData() {
        return [
            "id" => $this->id,
            "genreId" => $this->genreId,
            "genre" => $this->genre,
            "round" => $this->round,
            "position" => $this->position,
            "flipped" => $this->flipped,
            "cssClass" => $this->cssClass,
        ];
    }

    /**
     * Derive the CSS class for the calendar tile
     *
     * @return string The CSS class
     */
    private function deriveCssClass() {
        $cssClass = "aoc-calendar-tile";
        if ($this->flipped) {
            return $cssClass .= "-" . $this->genre;
        }
        return $cssClass .= "-facedown";
    }
}
