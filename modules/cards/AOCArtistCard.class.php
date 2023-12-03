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
 * Object class for Artist cards.
 *
 * Contains:
 * - The creative key of the artist card, this maps to a specific artist card
 * - The value of the artist card, this is the first digit of the creative key
 * - The number of fans the artist card provides when used for a comic of their genre. Always 1.
 * - The number of ideas the artist card provides when hired. Will always be 1 for 1-value artists and 0 for all others.
 * - The base CSS class for the artist card. This is the front of the card.
 * - The CSS class for the artist card when it is face down. This is the back of the card.
 * - The sorting order of the artist card when it is in a player's hand
 *
 * Artists are one of the two types of creatives in the game. They are required to create comics.
 *
 * Artist cards are represented by the "card" table in the database.
 *
 * Extends the generic AOCCard class.
 * @see AOCCard
 *
 * Their are 4 Artist cards for each genre with a value breakdown as follows:
 * - 1x 1-value artist
 * - 2x 2-value artists
 * - 1x 3-value artist
 *
 * Each player starts the game with a random 2-value artist.
 *
 * During the game, more artists can be hired from the supply using the Hire action.
 *
 * When a player prints a comic they are required to assign an Artist card from their hand to it.
 * If the artist card matches the genre of the comic, the comic gains +1 fan.
 *
 * @EvanPulgino
 */

class AOCArtistCard extends AOCCard {
    /**
     * @var int $creativeKey The creative key of the artist card, this maps to a specific artist card
     */
    private $creativeKey;

    /**
     * @var int $value The value of the artist card, this is the first digit of the creative key
     */
    private $value;

    /**
     * @var int $fans The number of fans the artist card provides when used for a comic of their genre. Always 1.
     */
    private $fans;

    /**
     * @var int $ideas The number of ideas the artist card provides when hired. Will always be 1 for 1-value artists and 0 for all others.
     */
    private $ideas;

    /**
     * @var string $baseClass The base CSS class for the artist card. This is the front of the card.
     */
    private $baseClass;

    /**
     * @var string $facedownClass The CSS class for the artist card when it is face down. This is the back of the card.
     */
    private $facedownClass;

    /**
     * @var int $handSortOrder The sorting order of the artist card when it is in a player's hand
     */
    private $handSortOrder;

    public function __construct($row) {
        parent::__construct($row);
        $this->creativeKey = (int) $row["typeArg"];
        $this->value = floor($this->creativeKey / 10);
        $this->fans = 1;
        $this->ideas = $this->value == 1 ? 1 : 0;
        $this->baseClass =
            "aoc-" .
            $this->getType() .
            "-" .
            $this->getGenre() .
            "-" .
            $this->creativeKey;
        $this->facedownClass =
            "aoc-" . $this->getType() . "-facedown-" . $this->value;
        $this->handSortOrder =
            $this->getTypeId() * 100 + $this->getGenreId() + $this->value;
    }

    /**
     * @return int The creative key of the artist card, this maps to a specific artist card
     */
    public function getCreativeKey() {
        return $this->creativeKey;
    }

    /**
     * @return int The value of the artist card, this is the first digit of the creative key
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @return int The number of fans the artist card provides when used for a comic of their genre. Always 1.
     */
    public function getFans() {
        return $this->fans;
    }

    /**
     * @return int The number of ideas the artist card provides when hired. Will always be 1 for 1-value artists and 0 for all others.
     */
    public function getIdeas() {
        return $this->ideas;
    }

    /**
     * @return string The base CSS class for the artist card. This is the front of the card.
     */
    public function getBaseClass() {
        return $this->baseClass;
    }

    /**
     * @return string The CSS class for the artist card when it is face down. This is the back of the card.
     */
    public function getFacedownClass() {
        return $this->facedownClass;
    }

    /**
     * @return int The sorting order of the artist card when it is in a player's hand
     */
    public function getHandSortOrder() {
        return $this->handSortOrder;
    }

    /**
     * Get the UI data for the artist card.
     *
     * @param int $currentPlayerId The ID of the current player (the player viewing the card)
     * @return array The UI data for the artist card
     */
    public function getUiData($currentPlayerId) {
        return [
            "id" => $this->getId(),
            "typeId" => $this->getTypeId(),
            "type" => $this->getType(),
            "genreId" => $this->getGenreId(),
            "genre" => $this->getGenre(),
            "location" => $this->getLocation(),
            "locationArg" => $this->getLocationArg(),
            "playerId" => $this->getPlayerId(),
            "creativeKey" => $this->getCreativeKey(),
            "value" => $this->getValue(),
            "fans" => $this->getFans(),
            "ideas" => $this->getIdeas(),
            "baseClass" => $this->getBaseClass(),
            "facedownClass" => $this->getFacedownClass(),
            "cssClass" => $this->deriveCssClass($currentPlayerId),
            "handSortOrder" => $this->getHandSortOrder(),
        ];
    }

    /**
     * Derive the CSS class for the artist card based on its location and the current player.
     *
     * The CSS class is derived as follows:
     * - If the card is in the supply, discard, or player mat, it is always the base class (front of the card)
     * - If the card is in the hand of the current player, it is always the base class (front of the card)
     * - If the card is in the hand of another player, it is always the facedown class (back of the card)
     * - If the card is in any other location, it is always the facedown class (back of the card)
     *
     * @param int $currentPlayerId The ID of the current player (the player viewing the card)
     * @return string The CSS class for the artist card based on its location and the current player
     */
    private function deriveCssClass($currentPlayerId) {
        switch ($this->getLocation()) {
            case LOCATION_SUPPLY:
                return $this->baseClass;
            case LOCATION_DISCARD:
                return $this->baseClass;
            case LOCATION_PLAYER_MAT:
                return $this->baseClass;
            case LOCATION_HAND:
                return $this->getPlayerId() == $currentPlayerId
                    ? $this->baseClass
                    : $this->facedownClass;
            default:
                return $this->facedownClass;
        }
    }
}
