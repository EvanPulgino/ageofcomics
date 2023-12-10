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
 * Object class for Ripoff cards.
 *
 * Contains:
 * - The ripoff key of the ripoff card, this maps to a specific comic card
 * - The name of the ripoff card
 * - The base CSS class for the ripoff card. This is the front of the card.
 * - The CSS class for the ripoff card when it is face down. This is the back of the card.
 *
 * Ripoff cards are a type of card in the game. They are created by players and provide fans and victory points.
 *
 * Ripoff cards are represented by the "card" table in the database.
 *
 * Extends the generic AOCCard class.
 * @see AOCCard
 *
 * There is one Ripoff card for each Comic card in the game.
 *
 * Once a Comic has been printed by a player any other player is eligible to print the Ripoff version of the card.
 * By using the Print action a player can print a Ripoff of an existing comic by taking the matching card and assignig a Writer card from their hand to it.
 * The cost of the Ripoff is money equal to the combined value of the artist and the writer.
 *
 * Ripoff cards provide players with fans but no bonus.
 *
 * @EvanPulgino
 */

class AOCRipoffCard extends AOCCard {
    /**
     * @var int $ripoffKey The ripoff key of the ripoff card, this maps to a specific comic card
     */
    private $ripoffKey;

    /**
     * @var string $name The name of the ripoff card
     */
    private $name;

    /**
     * @var int $fans The number of fans the ripoff card provides
     */
    private $fans;

    /**
     * @var string $baseClass The base CSS class for the ripoff card. This is the front of the card.
     */
    private $baseClass;

    /**
     * @var string $facedownClass The CSS class for the ripoff card when it is face down. This is the back of the card.
     */
    private $facedownClass;

    public function __construct($row) {
        parent::__construct($row);
        $this->ripoffKey = (int) $row["typeArg"];
        $this->name = RIPOFF_CARDS[$this->getGenreId()][$this->ripoffKey];
        $this->fans = 0;
        $this->baseClass =
            "aoc-" .
            $this->getType() .
            "-" .
            $this->getGenre() .
            "-" .
            $this->ripoffKey;
        $this->facedownClass =
            "aoc-" . $this->getType() . "-" . $this->getGenre() . "-facedown";
    }

    /**
     * @return int The ripoff key of the ripoff card, this maps to a specific comic card
     */
    public function getRipoffKey() {
        return $this->ripoffKey;
    }

    /**
     * @return string The name of the ripoff card
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string The base CSS class for the ripoff card. This is the front of the card.
     */
    public function getBaseClass() {
        return $this->baseClass;
    }

    /**
     * @return string The CSS class for the ripoff card when it is face down. This is the back of the card.
     */
    public function getFacedownClass() {
        return $this->facedownClass;
    }

    /**
     *
     * @return int The number of fans the ripoff card provides
     */
    public function getFans() {
        return $this->fans;
    }

    /**
     * Get the data for the UI
     *
     * @param int $currentPlayerId The ID of the current player (the player viewing the card)
     * @return array The data for the UI
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
            "ripoffKey" => $this->getRipoffKey(),
            "name" => $this->getName(),
            "fans" => $this->getFans(),
            "baseClass" => $this->baseClass,
            "facedownClass" => $this->facedownClass,
            "cssClass" => $this->deriveCssClass($currentPlayerId),
        ];
    }

    /**
     * Derive the CSS class for the ripoff card based on its location and the current player.
     *
     * The ripoff card is always face up on the player mat, and always face down in the deck.
     *
     * @param int $currentPlayerId The ID of the current player (the player viewing the card)
     * @return string The CSS class for the ripoff card
     */
    private function deriveCssClass($currentPlayerId) {
        if ($this->getLocation() == LOCATION_PLAYER_MAT) {
            return $this->baseClass;
        }
        return $this->facedownClass;
    }
}
