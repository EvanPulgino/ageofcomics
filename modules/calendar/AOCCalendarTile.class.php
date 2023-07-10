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
 * AOCCalendarTile.class.php
 *
 * Calendar tile class
 *
 */

class AOCCalendarTile {
    private int $id;
    private int $genreId;
    private string $genre;
    private int $round;
    private int $position;
    private string $cssClass;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->genreId = (int) $row["genre"];
        $this->genre = $this->getGenreName($this->genreId);
        $this->round = (int) $row["round"];
        $this->position = (int) $row["position"];
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
    public function getRound() {
        return $this->round;
    }
    public function getPosition() {
        return $this->position;
    }
    public function getCssClass() {
        return $this->cssClass;
    }

    public function getUiData() {
        return [
            "id" => $this->id,
            "genreId" => $this->genreId,
            "genre" => $this->genre,
            "round" => $this->round,
            "position" => $this->position,
            "cssClass" => $this->cssClass,
        ];
    }

    private function getGenreName($key) {
        switch ($key) {
            case GENRE_CRIME:
                return clienttranslate(CRIME);
            case GENRE_HORROR:
                return clienttranslate(HORROR);
            case GENRE_ROMANCE:
                return clienttranslate(ROMANCE);
            case GENRE_SCIFI:
                return clienttranslate(SCIFI);
            case GENRE_SUPERHERO:
                return clienttranslate(SUPERHERO);
            case GENRE_WESTERN:
                return clienttranslate(WESTERN);
            default:
                return "";
        }
    }
}
