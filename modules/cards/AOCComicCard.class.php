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
 * Object class for Comic cards.
 *
 * Contains:
 * - The bonus key of the comic card, this maps to a specific bonus
 * - The number of fans the comic card provides, always 1 unless the bonus is +1 fan
 * - The name of the comic
 * - The base CSS class for the comic card. This is the front of the card.
 * - The CSS class for the comic card when it is face down. This is the back of the card.
 *
 * Comics are a type of card in the game. They are created by players and provide fans and victory points.
 *
 * Comic cards are represented by the "card" table in the database.
 *
 * Extends the generic AOCCard class.
 * @see AOCCard
 *
 * Their are 4 Comic cards for each genre with a bonus breakdown as follows:
 * - 1x +1 fan
 * - 1x Gain 2 ideas
 * - 1x Super transport ticket
 * - 1x Gain 4 money
 *
 * Each player starts the game a random comic of a genre of their choice.
 *
 * During the game, comics can be gained from the supply using the Develop action.
 *
 * Players can print comics using the Print action.
 *
 * When a comic is printed, it is assigned a Writer card from the player's hand. It will cost 2 ideas of the comic's genre and money equal to the combined value of the artist and the writer.
 * Printing a comic will provide the player with fans and a bonus.
 *
 * @EvanPulgino
 */

class AOCComicCard extends AOCCard {
    /**
     * @var int $bonus The bonus key of the comic card:
     *
     * 1 = +1 fan
     * 2 = Gain 2 ideas
     * 3 = Super transport ticket
     * 4 = Gain 4 money
     */
    private $bonus;

    /**
     * @var int $fans The number of fans the comic card provides, always 1 unless the bonus is +1 fan
     */
    private $fans;

    /**
     * @var string $name The name of the comic
     */
    private $name;

    /**
     * @var string $baseClass The base CSS class for the comic card. This is the front of the card.
     */
    private $baseClass;

    /**
     * @var string $facedownClass The CSS class for the comic card when it is face down. This is the back of the card.
     */
    private $facedownClass;

    /**
     * @var int $handSortOrder The sorting order of the comic card when it is in a player's hand
     */
    private $handSortOrder;

    public function __construct($row) {
        parent::__construct($row);
        $this->bonus = (int) $row["typeArg"];
        $this->fans = 1;
        $this->name = COMIC_CARDS[$this->getGenreId()][$this->bonus];
        $this->baseClass =
            "aoc-" .
            $this->getType() .
            "-" .
            $this->getGenre() .
            "-" .
            $this->bonus;
        $this->facedownClass =
            "aoc-" . $this->getType() . "-" . $this->getGenre() . "-facedown";
        $this->handSortOrder =
            $this->getTypeId() * 100 + $this->getGenreId() + $this->bonus;
    }

    /**
     * @return int The bonus key of the comic card:
     *
     * 1 = +1 fan
     * 2 = Gain 2 ideas
     * 3 = Super transport ticket
     * 4 = Gain 4 money
     */
    public function getBonus() {
        return $this->bonus;
    }

    /**
     * @return int The number of fans the comic card provides, always 1 unless the bonus is +1 fan
     */
    public function getFans() {
        return $this->fans;
    }

    /**
     * @return string The name of the comic
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string The base CSS class for the comic card. This is the front of the card.
     */
    public function getBaseClass() {
        return $this->baseClass;
    }

    /**
     * @return string The CSS class for the comic card when it is face down. This is the back of the card.
     */
    public function getFacedownClass() {
        return $this->facedownClass;
    }

    /**
     * @return int The sorting order of the comic card when it is in a player's hand
     */
    public function getHandSortOrder() {
        return $this->handSortOrder;
    }

    /**
     * Get the UI data for the comic card
     *
     * @param int $currentPlayerId The database ID of the current player (the player viewing the card)
     * @return array The UI data for the comic card
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
            "bonus" => $this->getBonus(),
            "fans" => $this->getFans(),
            "name" => $this->getName(),
            "baseClass" => $this->baseClass,
            "facedownClass" => $this->facedownClass,
            "cssClass" => $this->deriveCssClass($currentPlayerId),
            "handSortOrder" => $this->getHandSortOrder(),
        ];
    }

    /**
     * Derive the CSS class for the comic card based on its location and the current player.
     *
     * The CSS class is derived as follows:
     * - If the card is in the supply, discard, or player mat, it is always the base class (front of the card)
     * - If the card is in the hand of the current player, it is always the base class (front of the card)
     * - If the card is in the hand of another player, it is always the facedown class (back of the card)
     * - If the card is in any other location, it is always the facedown class (back of the card)
     *
     * @param int $currentPlayerId The database ID of the current player (the player viewing the card)
     * @return string The CSS class for the comic card
     */
    private function deriveCssClass($currentPlayerId) {
        switch ($this->getLocation()) {
            case LOCATION_DISCARD:
                return $this->baseClass;
            case LOCATION_SUPPLY:
                return $this->baseClass;
            case LOCATION_PLAYER_MAT:
                return $this->baseClass;
            case LOCATION_HYPE:
                return $this->baseClass;
            case LOCATION_HAND:
                if ($this->getPlayerId() == $currentPlayerId) {
                    return $this->baseClass;
                } else {
                    return $this->facedownClass;
                }
            default:
                return $this->facedownClass;
        }
    }
}
