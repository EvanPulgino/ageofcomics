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
    private int $creativeKey;
    private int $value;
    private int $fans;
    private int $ideas;
    private string $baseClass;
    private string $facedownClass;

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
            "aoc-" .
            $this->getType() .
            "-facedown-" .
            $this->value;
    }

    public function getCreativeKey() {
        return $this->creativeKey;
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
            "facedownClass" => $this->facedownClass,
            "cssClass" => $this->deriveCssClass($currentPlayerId),
        ];
    }

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
