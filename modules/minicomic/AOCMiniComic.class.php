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
 * AOCMiniComic.class.php
 *
 * Mini comic object
 *
 */

class AOCMiniComic extends AOCObject {
    private int $id;
    private int $typeId;
    private string $type;
    private int $comicKey;
    private int $genreId;
    private string $genre;
    private int $location;
    private int $locationArg;
    private int $playerId;
    private int $fans;
    private string $name;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->typeId = (int) $row["type"];
        $this->type = $this->getTypeName($this->typeId);
        $this->comicKey = (int) $row["typeArg"];
        $this->genreId = (int) $row["genre"];
        $this->genre = $this->getGenreName($this->genreId);
        $this->location = (int) $row["location"];
        $this->locationArg = (int) $row["locationArg"];
        $this->playerId = (int) $row["playerId"];
        $this->fans = (int) $row["fans"];
        $this->name = $this->getComicName(
            $this->typeId,
            $this->genreId,
            $this->comicKey
        );
    }

    public function getId() {
        return $this->id;
    }
    public function getTypeId() {
        return $this->typeId;
    }
    public function getType() {
        return $this->type;
    }
    public function getComicKey() {
        return $this->comicKey;
    }
    public function getGenreId() {
        return $this->genreId;
    }
    public function getGenre() {
        return $this->genre;
    }
    public function getLocation() {
        return $this->location;
    }
    public function setLocation($location) {
        $this->location = $location;
    }
    public function getLocationArg() {
        return $this->locationArg;
    }
    public function setLocationArg($locationArg) {
        $this->locationArg = $locationArg;
    }
    public function getPlayerId() {
        return $this->playerId;
    }
    public function setPlayerId($playerId) {
        $this->playerId = $playerId;
    }
    public function getFans() {
        return $this->fans;
    }
    public function setFans($fans) {
        $this->fans = $fans;
    }
    public function getName() {
        return $this->name;
    }

    public function getUiData() {
        return [
            "id" => $this->getId(),
            "typeId" => $this->getTypeId(),
            "type" => $this->getType(),
            "comicKey" => $this->getComicKey(),
            "genreId" => $this->getGenreId(),
            "genre" => $this->getGenre(),
            "location" => $this->getLocation(),
            "locationArg" => $this->getLocationArg(),
            "playerId" => $this->getPlayerId(),
            "fans" => $this->getFans(),
            "name" => $this->getName(),
        ];
    }

    private function getComicName($typeId, $genreId, $comicKey) {
        if ($typeId == CARD_TYPE_COMIC) {
            return COMIC_CARDS[$genreId][$comicKey];
        } else {
            return RIPOFF_CARDS[$genreId][$comicKey];
        }
    }

    private function getTypeName($key) {
        switch ($key) {
            case CARD_TYPE_COMIC:
                return clienttranslate(COMIC);
            case CARD_TYPE_RIPOFF:
                return clienttranslate(RIPOFF);
        }
    }
}
