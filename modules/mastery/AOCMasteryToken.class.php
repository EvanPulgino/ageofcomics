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
 * Object class for a Mastery Token tile.
 * Contains:
 * - The database ID of the mastery token
 * - The genre key of the mastery token
 * - The genre name of the mastery token
 * - The ID of the player who owns the mastery token, NULL if unowned
 * - The number of comics the owning player has in the genre
 *
 * The Mastery Token tile represents a player's mastery of a genre of comic books.
 *
 * There is one Mastery Token tile for each genre of comic books in the game.
 *
 * Mastery Token tiles are represented by the "mastery_token" table in the database.
 *
 * A Mastery Token tile is gained by a player when they have printed the most comics of a genre, and at least one of them is an original comic.
 * The tile is gained from the supply or from another player who owns it.
 *
 * When a player gains a Mastery Token tile, all of their printed comics of that genre each gain one fan. Each time that player prints a comic of that genre, it gains one additional fan.
 * If the player loses the Mastery Token tile, their comics of that genre do not lose the additional fan.
 *
 * At the end of the game, each player gains 2 points for each Mastery Token tile they own.
 *
 * @EvanPulgino
 */

class AOCMasteryToken {
    /**
     * @var int $id The database ID of the mastery token
     */
    private $id;

    /**
     * @var int $genreId The genre key of the mastery token
     * @see GENRE_KEYS
     */
    private $genreId;

    /**
     * @var string $genre The genre name of the mastery token
     */
    private $genre;

    /**
     * @var int $playerId The ID of the player who owns the mastery token, NULL if unowned
     */
    private $playerId;

    /**
     * @var int $comicCount The number of comics the owning player has in the genre
     */
    private $comicCount;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->genreId = (int) $row["genre"];
        $this->genre = GENRES[$this->genreId];
        $this->playerId = (int) $row["playerId"];
        $this->comicCount = (int) $row["comicCount"];
    }

    /**
     * Get the database ID of the mastery token
     *
     * @return int The database ID of the mastery token
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the genre key of the mastery token
     *
     * @return int The genre key of the mastery token
     */
    public function getGenreId() {
        return $this->genreId;
    }

    /**
     * Get the genre name of the mastery token
     *
     * @return string The genre name of the mastery token
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Get the ID of the player who owns the mastery token, NULL if unowned
     *
     * @return int The ID of the player who owns the mastery token, NULL if unowned
     */
    public function getPlayerId() {
        return $this->playerId;
    }

    /**
     * Set the ID of the player who owns the mastery token, NULL if unowned
     *
     * @param int $playerId The ID player who owns the mastery token, NULL if unowned
     * @return void
     */
    public function setPlayerId($playerId) {
        $this->playerId = $playerId;
    }

    /**
     * Get the number of comics the owning player has in the genre
     *
     * @return int The number of comics the owning player has in the genre
     */
    public function getComicCount() {
        return $this->comicCount;
    }

    /**
     * Set the number of comics the owning player has in the genre
     *
     * @param int $comicCount The number of comics the owning player has in the genre
     * @return void
     */
    public function setComicCount($comicCount) {
        $this->comicCount = $comicCount;
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
