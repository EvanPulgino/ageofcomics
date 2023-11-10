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
 * Object class for a Mini Comic tile.
 * Contains:
 * - The database ID of the mini comic
 * - The type ID of the mini comic
 * - The type name of the mini comic
 * - The comic key of the mini comic, this maps to a specific comic in the COMBINED_COMIC_CARDS array
 * - The genre key of the mini comic
 * - The genre name of the mini comic
 * - The location of the mini comic
 * - The location argument of the mini comic
 * - The ID of the player who owns the mini comic, NULL if unowned
 * - The number of fans the mini comic has
 * - The name of the mini comic
 * - The CSS class for the mini comic
 *
 * A Mini Comic tile represents a comic book (original or rip-off) that a player can create to gain fans. There is one tile for each comic book card in the game.
 * 
 * Mini Comic tiles are represented by the "mini_comic" table in the database.
 *
 * When a player uses the Print action to create a comic, the corresponing Mini Comic tile is placed in that player's column of the chart, which tracks how many fans the comic has and how much income the comic produces at the end of every round.
 *
 * At the end of every round, each player's highest value Mini Comic tiles are ranked and awarded points.
 *
 * At the end of every round every Mini Comictiles is reduced on the chart by one fan.
 *
 * @EvanPulgino
 */

class AOCMiniComic {
    /**
     * @var int $id The database ID of the mini comic
     */
    private $id;

    /**
     * @var int $typeId The type of the mini comic:
     * - 3 = Original
     * - 4 = Ripoff
     */
    private $typeId;

    /**
     * @var string $type The type name of the mini comic
     */
    private $type;

    /**
     * @var int $comicKey The comic key of the mini comic, this maps to a specific comic in the COMBINED_COMIC_CARDS array
     */
    private $comicKey;

    /**
     * @var int $genreId The genre key of the mini comic:
     * - 10 = Crime
     * - 20 = Horror
     * - 30 = Romance
     * - 40 = Sci-Fi
     * - 50 = Superhero
     * - 60 = Western
     */
    private $genreId;

    /**
     * @var string $genre The genre name of the mini comic
     */
    private $genre;

    /**
     * @var int $location The location of the mini comic:
     * - 3 = In the general supply
     * - 6 = On the chart
     */
    private $location;

    /**
     * @var int $locationArg The location argument of the mini comic:
     * - If location is 3, locationArg is the order of the mini comic in it's genre stack
     * - If location is 6, this is NULL
     */
    private $locationArg;

    /**
     * @var int $playerId The ID of the player who owns the mini comic, NULL if unowned
     */
    private $playerId;

    /**
     * @var int $fans The number of fans the mini comic has
     */
    private $fans;

    /**
     * @var string $name The name of the mini comic
     */
    private $name;

    /**
     * @var string $cssClass The CSS class of the mini comic
     */
    private $cssClass;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->typeId = (int) $row["type"];
        $this->type = CARD_TYPE_TO_NAME[$this->typeId];
        $this->comicKey = (int) $row["typeArg"];
        $this->genreId = (int) $row["genre"];
        $this->genre = GENRES[$this->genreId];
        $this->location = (int) $row["location"];
        $this->locationArg = (int) $row["locationArg"];
        $this->playerId = (int) $row["playerId"];
        $this->fans = (int) $row["fans"];
        $this->name =
            COMBINED_COMIC_CARDS[$this->typeId][$this->genreId][
                $this->comicKey
            ];
        $this->cssClass = $this->deriveCssClass();
    }

    /**
     * Get the database ID of the mini comic
     *
     * @return int The database ID of the mini comic
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the type ID of the mini comic:
     * - 3 = Original
     * - 4 = Ripoff
     *
     * @return int The type ID of the mini comic
     */
    public function getTypeId() {
        return $this->typeId;
    }

    /**
     * Get the type name of the mini comic
     *
     * @return string The type name of the mini comic
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Get the comic key of the mini comic, this maps to a specific comic in the COMBINED_COMIC_CARDS array
     *
     * @return int The comic key of the mini comic
     */
    public function getComicKey() {
        return $this->comicKey;
    }

    /**
     * Get the genre key of the mini comic:
     * - 10 = Crime
     * - 20 = Horror
     * - 30 = Romance
     * - 40 = Sci-Fi
     * - 50 = Superhero
     * - 60 = Western
     *
     * @return int The genre key of the mini comic
     */
    public function getGenreId() {
        return $this->genreId;
    }

    /**
     * Get the genre name of the mini comic
     *
     * @return string The genre name of the mini comic
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Get the location of the mini comic:
     * - 3 = In the general supply
     * - 6 = On the chart
     *
     * @return int The location of the mini comic
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Set the location of the mini comic:
     * - 3 = In the general supply
     * - 6 = On the chart
     *
     * @param int $location The location of the mini comic
     * @return void
     */
    public function setLocation($location) {
        $this->location = $location;
    }

    /**
     * Get the location argument of the mini comic:
     * - If location is 3, locationArg is the order of the mini comic in it's genre stack
     * - If location is 6, this is NULL
     *
     * @return int The location argument of the mini comic
     */
    public function getLocationArg() {
        return $this->locationArg;
    }

    /**
     * Set the location argument of the mini comic:
     * - If location is 3, locationArg is the order of the mini comic in it's genre stack
     * - If location is 6, this is NULL
     *
     * @param int $locationArg The location argument of the mini comic
     * @return void
     */
    public function setLocationArg($locationArg) {
        $this->locationArg = $locationArg;
    }

    /**
     * Get the ID of the player who owns the mini comic, NULL if unowned
     *
     * @return int The ID of the player who owns the mini comic, NULL if unowned
     */
    public function getPlayerId() {
        return $this->playerId;
    }

    /**
     * Set the ID of the player who owns the mini comic, NULL if unowned
     *
     * @param int $playerId The ID of the player who owns the mini comic, NULL if unowned
     * @return void
     */
    public function setPlayerId($playerId) {
        $this->playerId = $playerId;
    }

    /**
     * Get the number of fans the mini comic has
     *
     * @return int The number of fans the mini comic has
     */
    public function getFans() {
        return $this->fans;
    }

    /**
     * Set the number of fans the mini comic has
     *
     * @param int $fans The number of fans the mini comic has
     * @return void
     */
    public function setFans($fans) {
        $this->fans = $fans;
    }

    /**
     * Get the name of the mini comic
     *
     * @return string The name of the mini comic
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get the CSS class of the mini comic
     *
     * @return string The CSS class of the mini comic
     */
    public function getCssClass() {
        return $this->cssClass;
    }

    public function getUiData() {
        return [
            "id" => $this->getId(),
            "typeId" => $this->getTypeId(),
            "type" => $this->getType(),
            "comicKey" => $this->getComicKey(),
            "genreId" => $this->getGenreId(),
            "genre" => $this->getGenre(),
            "location" => $this->getLocation(),
            "locationArg" => $this->getLocationArg(),
            "playerId" => $this->getPlayerId(),
            "fans" => $this->getFans(),
            "name" => $this->getName(),
            "cssClass" => $this->getCssClass(),
        ];
    }

    /**
     * Derives the CSS class for this mini comic based on its type, genre, and comic key.
     *
     * @return string The CSS class for this mini comic.
     */
    private function deriveCssClass() {
        $base =
            $this->typeId == CARD_TYPE_COMIC
                ? "aoc-mini-comic"
                : "aoc-mini-ripoff";
        $class = $base . "-" . $this->genreId . "-" . $this->comicKey;
        if ($this->fans > 10) {
            return $class . "-flipped";
        }
        return $class;
    }
}
