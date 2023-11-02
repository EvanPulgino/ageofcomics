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
 * AOCComicCard.class.php
 *
 * Comic card object
 *
 */

class AOCComicCard extends AOCCard {
    private int $bonus;
    private int $fans;
    private string $name;
    private string $baseClass;
    private string $facedownClass;

    public function __construct($row) {
        parent::__construct($row);
        $this->bonus = (int) $row["typeArg"];
        $this->fans = $this->bonus == PLUS_ONE_FAN ? 2 : 1;
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
    }

    public function getBonus() {
        return $this->bonus;
    }
    public function getFans() {
        return $this->fans;
    }
    public function getName() {
        return $this->name;
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
            "bonus" => $this->getBonus(),
            "fans" => $this->getFans(),
            "name" => $this->getName(),
            "facedownClass" => $this->facedownClass,
            "cssClass" => $this->deriveCssClass($currentPlayerId),
        ];
    }

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
