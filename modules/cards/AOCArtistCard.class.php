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
 * AOCArtistCard.class.php
 *
 * Artist card object
 *
 */

class AOCArtistCard extends AOCCard {
    private int $value;
    private int $fans;
    private int $ideas;

    public function __construct($row) {
        parent::__construct($row);
        $this->value = (int) $row["typeArg"];
        $this->fans = 1;
        $this->ideas = $this->value == 1 ? 1 : 0;
    }

    public function getValue() {
        return $this->value;
    }
    public function getFans() {
        return $this->fans;
    }
    public function getIdeas() {
        return $this->ideas;
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
            "value" => $this->getValue(),
            "fans" => $this->getFans(),
            "ideas" => $this->getIdeas(),
        ];
    }
}
