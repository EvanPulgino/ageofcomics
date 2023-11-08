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
 * AOCSalesOrder object class
 *
 * @EvanPulgino
 */

class AOCSalesOrder {
    private int $id;
    private int $genreId;
    private string $genre;
    private int $value;
    private int $fans;
    private int $playerId;
    private int $location;
    private int $locationArg;
    private int $flipped;
    private string $cssClass;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->genreId = (int) $row["genre"];
        $this->genre = GENRES[$this->genreId];
        $this->value = (int) $row["value"];
        $this->fans = (int) $row["fans"];
        $this->playerId = (int) $row["playerId"];
        $this->location = (int) $row["location"];
        $this->locationArg = (int) $row["locationArg"];
        $this->flipped = (int) $row["flipped"];
        $this->cssClass = $this->deriveCssClass();
    }

    public function getId() {
        return $this->id;
    }
    public function getGenreId() {
        return $this->genreId;
    }
    public function getGenre() {
        return $this->genre;
    }
    public function getValue() {
        return $this->value;
    }
    public function getFans() {
        return $this->fans;
    }
    public function getPlayerId() {
        return $this->playerId;
    }
    public function setPlayerId($playerId) {
        $this->playerId = $playerId;
    }
    public function getLocation() {
        return $this->location;
    }
    public function getLocationArg() {
        return $this->locationArg;
    }
    public function setLocation($location) {
        $this->location = $location;
    }
    public function setLocationArg($locationArg) {
        $this->locationArg = $locationArg;
    }
    public function isFlipped() {
        return $this->flipped;
    }
    public function setFlipped($flipped) {
        $this->flipped = $flipped;
    }

    public function getCssClass() {
        return $this->cssClass;
    }

    /**
     * Get the data formatted for the UI
     *
     * @return array
     */
    public function getUiData() {
        return [
            "id" => $this->id,
            "genreId" => $this->genreId,
            "genre" => $this->genre,
            "value" => $this->value,
            "fans" => $this->fans,
            "playerId" => $this->playerId,
            "location" => $this->location,
            "locationArg" => $this->locationArg,
            "flipped" => $this->flipped,
            "cssClass" => $this->cssClass,
        ];
    }

    /**
     * Derive the CSS class for the sales order based on its genre and flipped status
     *
     * @return string The CSS class for the sales order
     */
    private function deriveCssClass() {
        $cssClass = "aoc-salesorder-" . $this->genre;
        if ($this->flipped) {
            return $cssClass .= "-" . $this->value;
        } else {
            return $cssClass .= "-facedown";
        }
    }
}
