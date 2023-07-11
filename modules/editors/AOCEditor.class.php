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
 * AOCEditor.class.php
 *
 * Editor meeple class
 *
 */

class AOCEditor extends AOCObject {
    private int $id;
    private int $playerId;
    private int $locationId;
    private string $cssClass;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->playerId = (int) $row["owner"];
        $this->locationId = (int) $row["location"];
        $this->cssClass = $row["class"];
    }

    public function getId() {
        return $this->id;
    }
    public function getPlayerId() {
        return $this->playerId;
    }
    public function getLocationId() {
        return $this->locationId;
    }
    public function getCssClass() {
        return $this->cssClass;
    }

    public function getUiData() {
        return [
            "id" => $this->id,
            "playerId" => $this->playerId,
            "locationId" => $this->locationId,
            "cssClass" => $this->cssClass,
        ];
    }
}
