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
 * AOCSalesOrder.class.php
 *
 * AOCSalesOrder class
 *
 */

class AOCSalesOrder extends AOCObject {
    private int $id;
    private int $genreId;
    private string $genre;
    private int $value;
    private int $fans;
    private int $playerId;
    private int $location;
    private string $cssClass;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->genreId = (int) $row["genre"];
        $this->genre = $this->getGenreName($this->genreId);
        $this->value = (int) $row["value"];
        $this->fans = (int) $row["fans"];
        $this->playerId = (int) $row["playerId"];
        $this->location = (int) $row["location"];
        $this->cssClass = $row["class"];
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
    public function getLocation() {
        return $this->location;
    }
    public function getCssClass() {
        return $this->cssClass;
    }

    public function getUiData() {
        return [
            "id" => $this->id,
            "genreId" => $this->genreId,
            "genre" => $this->genre,
            "value" => $this->value,
            "fans" => $this->fans,
            "playerId" => $this->playerId,
            "location" => $this->location,
            "cssClass" => $this->cssClass,
        ];
    }
}
