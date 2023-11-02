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
    private string $baseClass;
    private string $facedownClass;

    public function __construct($row) {
        parent::__construct($row);
        $this->ripoffKey = (int) $row["typeArg"];
        $this->name = RIPOFF_CARDS[$this->getGenreId()][$this->ripoffKey];
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

    public function getRipoffKey() {
        return $this->ripoffKey;
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
            "ripoffKey" => $this->getRipoffKey(),
            "name" => $this->getName(),
            "facedownClass" => $this->facedownClass,
            "cssClass" => $this->deriveCssClass($currentPlayerId),
        ];
    }

    private function deriveCssClass($currentPlayerId) {
        if ($this->getLocation() == LOCATION_PLAYER_MAT) {
            return $this->baseClass;
        }
        return $this->facedownClass;
    }
}
