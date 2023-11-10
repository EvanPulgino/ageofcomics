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
 * Object class for a Sales Order tile.
 * Contains:
 * - The database ID of the sales order
 * - The genre key of the sales order
 * - The genre name of the sales order
 * - The minimum value of the comic required to fulfill the sales order
 * - The number of fans the comic gains when the sales order is fulfilled
 * - The ID of the player who owns the sales order, NULL if unowned
 * - The location of the sales order
 * - The location argument of the sales order
 * - Whether the sales order is flipped face up or face down
 * - The CSS class for the sales order
 *
 * The Sales Order tile represents an order available on the market that you can fulfill with a comic book (original or rip-off) to gain immediate fans.
 *
 * A Sales Order tile has:
 * - A genre (Crime, Horror, Romance, Sci-Fi, Superhero, or Western)
 * - A minimum value of the comic required to fulfill the sales order
 * - A number of fans gained by the comic when the sales order is fulfilled
 *
 * Sales Order tiles are represented by the "sales_order" table in the database.
 *
 * There are 7 tiles for each genre, with values of 3, 3, 3, 4, 4, 5, and 6 within each genre. The number of fans gained is equal to the value of the tile minus two.
 *
 * All tiles are used in a 4-player game, with in a 3-player game one of the 3 value tiles is removed, and in a 2-player game one value 3 tile and one value 4 tile are removed.
 *
 * Sales Order tiles are randomly placed face down on the map (only on spaces matching the player count) at the beginning of the game, showing only the genre of the tile.
 * There cannot be three or moder Sales Order tiles adjacent the same circle space on the map. If this would happen, tile placement is re-randomized until this is no longer the case.
 *
 * At the start of each round a Calendar tile depecting a genre is revealed, and all Sales Order tiles of that genre are flipped face up so that it's genre, value, and fans are now visible.
 * Players may also flip and reveal individual Sales Order tiles as a part of the 'Sales' action. Players can also claim Sales Order tiles as a part of the 'Sales' action.
 *
 * A Sales Order is immediately fulfilled the instant a player has a claimed Sales Order tile and a created Comic (original or rip-off) of the same genre and at least the value as the Sales Order.
 * The Comic gains the number of fans depicted on the Sales Order tile, and the Sales Order tile is discarded.
 * If there are multiple eligible Comics, the player can decide which one to fulfill the Sales Order with.
 *
 * At the end of the game, the fan values of any claimed, unfulfilled Sales Order tiles are subtracted from the player's score.
 *
 * @EvanPulgino
 */

class AOCSalesOrder {
    /**
     * @var int $id The database ID of the sales order
     */
    private $id;

    /**
     * @var int $genreId The genre key of the sales order:
     * - 10 = Crime
     * - 20 = Horror
     * - 30 = Romance
     * - 40 = Sci-Fi
     * - 50 = Superhero
     * - 60 = Western
     */
    private $genreId;

    /**
     * @var string $genre The genre name of the sales order
     */
    private $genre;

    /**
     * @var int $value The minimum value of the comic required to fulfill the sales order
     */
    private $value;

    /**
     * @var int $fans The number of fans the comic gains when the sales order is fulfilled
     */
    private $fans;

    /**
     * @var int $playerId The ID of the player who owns the sales order, NULL if unowned
     */
    private $playerId;

    /**
     * @var int $location The location of the sales order:
     *  - 3 = In a player's supply
     *  - 9 = Somewhere on the map
     */
    private $location;

    /**
     * @var int $locationArg The location argument of the sales order:
     *  - If location is 3, this is NULL
     *  - If location is 9, this is the ID of the sales order space on the map
     */
    private $locationArg;

    /**
     * @var int $flipped Whether the sales order is flipped face up or face down:
     *  - 0 = Face down
     *  - 1 = Face up
     */
    private $flipped;

    /**
     * @var string $cssClass The CSS class for the sales order
     */
    private $cssClass;

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

    /**
     * Get the database ID of the sales order
     *
     * @return int The database ID of the sales order
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the genre key of the sales order
     *
     * @return int The genre key of the sales order
     */
    public function getGenreId() {
        return $this->genreId;
    }

    /**
     * Get the genre name of the sales order
     *
     * @return string The genre name of the sales order
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Get the minimum value of the comic required to fulfill the sales order
     *
     * @return int The minimum value of the comic required to fulfill the sales order
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Get the number of fans the comic gains when the sales order is fulfilled
     *
     * @return int The number of fans the comic gains when the sales order is fulfilled
     */
    public function getFans() {
        return $this->fans;
    }

    /**
     * Get the ID of the player who owns the sales order, NULL if unowned
     *
     * @return int The ID of the player who owns the sales order, NULL if unowned
     */
    public function getPlayerId() {
        return $this->playerId;
    }

    /**
     * Set the ID of the player who owns the sales order
     *
     * @param int $playerId The ID of the player who owns the sales order
     * @return void
     */
    public function setPlayerId($playerId) {
        $this->playerId = $playerId;
    }

    /**
     * Get the location of the sales order
     *
     * @return int The location of the sales order
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Set the location of the sales order
     *
     * @param int $locationArg The location of the sales order
     */
    public function setLocation($location) {
        $this->location = $location;
    }

    /**
     * Get the location argument of the sales order
     *
     * @return int The location argument of the sales order
     */
    public function getLocationArg() {
        return $this->locationArg;
    }

    /**
     * Set the location argument of the sales order
     *
     * @param int $locationArg The location argument of the sales order
     */
    public function setLocationArg($locationArg) {
        $this->locationArg = $locationArg;
    }

    /**
     * Get whether the sales order is flipped face up or face down
     *
     * @return int Whether the sales order is flipped face up or face down
     */
    public function isFlipped() {
        return $this->flipped;
    }

    /**
     * Set whether the sales order is flipped face up or face down
     *
     * @param int $flipped Whether the sales order is flipped face up or face down
     */
    public function setFlipped($flipped) {
        $this->flipped = $flipped;
    }

    /**
     * Get the CSS class for the sales order
     *
     * @return string The CSS class for the sales order
     */
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
