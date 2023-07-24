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
 * AOCCard.class.php
 *
 * Card object
 *
 */

class AOCCard extends AOCObject {
    private int $id;
    private int $typeId;
    private string $type;
    private int $genreId;
    private string $genre;
    private int $location;
    private int $locationArg;
    private int $playerId;
    private string $cssClass;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->typeId = (int) $row["type"];
        $this->type = $this->getCardTypeName($this->typeId);
        $this->genreId = (int) $row["genre"];
        $this->genre = $this->getGenreName($this->genreId);
        $this->location = (int) $row["location"];
        $this->locationArg = (int) $row["locationArg"];
        $this->playerId = (int) $row["playerId"];
        $this->cssClass = $row["class"];
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
    public function getCssClass() {
        return $this->cssClass;
    }
    public function setCssClass($cssClass) {
        $this->cssClass = $cssClass;
    }

    /**
     * Get uiData for this card
     * @return array
     */
    public function getUiData() {
        return [
            "id" => $this->id,
            "typeId" => $this->typeId,
            "type" => $this->type,
            "genreId" => $this->genreId,
            "genre" => $this->genre,
            "location" => $this->location,
            "locationArg" => $this->locationArg,
            "playerId" => $this->playerId,
            "cssClass" => $this->cssClass,
        ];
    }

    /**
     * Get the name of a card type from its key
     * @param $key int
     * @return string
     */
    private function getCardTypeName($key) {
        switch ($key) {
            case CARD_TYPE_ARTIST:
                return clienttranslate(ARTIST);
            case CARD_TYPE_COMIC:
                return clienttranslate(COMIC);
            case CARD_TYPE_RIPOFF:
                return clienttranslate(RIPOFF);
            case CARD_TYPE_WRITER:
                return clienttranslate(WRITER);
            default:
                return "";
        }
    }
}
