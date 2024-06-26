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
 * Object class for a Card.
 * Contains:
 * - The database ID of the card
 * - The type ID of the card
 * - The type of the card
 * - The genre ID of the card
 * - The genre of the card
 * - The location of the card
 * - The location argument of the card
 * - The ID of the player who owns the card
 * - The display value of the card - used when creatives are improved
 *
 * This is a generic class for all cards. Each card type has its own class that extends this class:
 * - @see AOCArtistCard
 * - @see AOCComicCard
 * - @see AOCRipoffCard
 * - @see AOCWriterCard
 *
 * Cards are represented by the "card" table in the database.
 *
 * @EvanPulgino
 */

class AOCCard {
    /**
     * @var int $id The database ID of the card
     */
    private $id;

    /**
     * @var int $typeId The type ID of the card
     * @see CARD_TYPES
     */
    private $typeId;

    /**
     * @var string $type The string value of typeId
     */
    private $type;

    /**
     * @var int $genreId The genre ID of the card
     * @see GENRES
     */
    private $genreId;

    /**
     * @var string $genre The string value of genreId
     */
    private $genre;

    /**
     * @var int $location The location of the card
     */
    private $location;

    /**
     * @var int $locationArg The location argument of the card
     */
    private $locationArg;

    /**
     * @var int $playerId The ID of the player who owns the card, null if no player owns the card
     */
    private $playerId;

    /**
     * @var int $displayValue The value of the creative card, including improvements
     */
    private $displayValue;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->typeId = (int) $row["type"];
        $this->type = CARD_TYPES[$this->typeId];
        $this->genreId = (int) $row["genre"];
        $this->genre = GENRES[$this->genreId];
        $this->location = (int) $row["location"];
        $this->locationArg = (int) $row["locationArg"];
        $this->playerId = (int) $row["playerId"];
        $this->displayValue = (int) $row["displayValue"];
    }

    /**
     * Get the database ID of the card
     *
     * @return int The database ID of the card
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the type ID of the card
     *
     * @return int The type ID of the card
     */
    public function getTypeId() {
        return $this->typeId;
    }

    /**
     * Get the type of the card
     *
     * @return string The type of the card
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Get the genre ID of the card
     *
     * @return int The genre ID of the card
     */
    public function getGenreId() {
        return $this->genreId;
    }

    /**
     * Get the genre of the card
     *
     * @return string The genre of the card
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Get the location of the card
     *
     * @return int The location of the card
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Set the location of the card
     *
     * @param int $location The location of the card
     * @return void
     */
    public function setLocation($location) {
        $this->location = $location;
    }

    /**
     * Get the location argument of the card
     *
     * @return int The location argument of the card
     */
    public function getLocationArg() {
        return $this->locationArg;
    }

    /**
     * Set the location argument of the card
     *
     * @param int $locationArg The location argument of the card
     * @return void
     */
    public function setLocationArg($locationArg) {
        $this->locationArg = $locationArg;
    }

    /**
     * Get the ID of the player who owns the card
     *
     * @return int The ID of the player who owns the card
     */
    public function getPlayerId() {
        return $this->playerId;
    }

    /**
     * Set the ID of the player who owns the card
     *
     * @param int $playerId The ID of the player who owns the card
     * @return void
     */
    public function setPlayerId($playerId) {
        $this->playerId = $playerId;
    }

    /**
     * Get the display value of the card
     *
     * @return int The display value of the card
     */
    public function getDisplayValue() {
        return $this->displayValue;
    }

    /**
     * Set the display value of the card
     *
     * @param int $displayValue The display value of the card
     * @return void
     */
    public function setDisplayValue($displayValue) {
        $this->displayValue = $displayValue;
    }

    /**
     * Get uiData for this card
     * @return array uiData for this card
     */
    public function getUiData($currentPlayerId) {
        return [
            "id" => $this->id,
            "typeId" => $this->typeId,
            "type" => $this->type,
            "genreId" => $this->genreId,
            "genre" => $this->genre,
            "location" => $this->location,
            "locationArg" => $this->locationArg,
            "playerId" => $this->playerId,
            "displayValue" => $this->displayValue,
        ];
    }
}
