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
 * Object class for mastery tokens
 *
 * @EvanPulgino
 */

class AOCMasteryToken {
    private int $id;
    private int $genreId;
    private string $genre;
    private int $playerId;
    private int $comicCount;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->genreId = (int) $row["genre"];
        $this->genre = GENRES[$this->genreId];
        $this->playerId = (int) $row["playerId"];
        $this->comicCount = (int) $row["comicCount"];
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
    public function getPlayerId() {
        return $this->playerId;
    }
    public function getComicCount() {
        return $this->comicCount;
    }

    /**
     * Get the data formatted for for the UI
     */
    public function getUiData() {
        return [
            "id" => $this->id,
            "genreId" => $this->genreId,
            "genre" => $this->genre,
            "playerId" => $this->playerId,
            "comicCount" => $this->comicCount,
        ];
    }
}
