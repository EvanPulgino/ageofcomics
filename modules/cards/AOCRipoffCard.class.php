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
 * AOCRipoffCard.class.php
 *
 * Ripoff card object
 *
 */

class AOCRipoffCard extends AOCCard {
    private int $ripoffKey;
    private string $name;

    public function __construct($row) {
        parent::__construct($row);
        $this->ripoffKey = (int) $row["typeArg"];
        $this->name = RIPOFF_CARDS[$this->getGenreId()][$this->ripoffKey];
    }

    public function getRipoffKey() {
        return $this->ripoffKey;
    }
    public function getName() {
        return $this->name;
    }

    public function getUiData() {
        return [
            "id" => $this->getId(),
            "typeId" => $this->getTypeId(),
            "type" => $this->getType(),
            "genreId" => $this->getGenreId(),
            "genre" => $this->getGenre(),
            "location" => $this->getLocation(),
            "locationArg" => $this->getLocationArg(),
            "playerId" => $this->getPlayerId(),
            "cssClass" => $this->getCssClass(),
            "ripoffKey" => $this->getRipoffKey(),
            "name" => $this->getName(),
        ];
    }
}
