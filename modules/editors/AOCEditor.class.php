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

class AOCEditor {
    private int $id;
    private int $playerId;
    private string $color;
    private int $locationId;
    private string $cssClass;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->playerId = (int) $row["owner"];
        $this->color = $row["color"];
        $this->locationId = (int) $row["location"];
        $this->cssClass = $this->deriveCssClass();
    }

    public function getId() {
        return $this->id;
    }
    public function getPlayerId() {
        return $this->playerId;
    }
    public function getColor() {
        return $this->color;
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
            "color" => $this->color,
            "locationId" => $this->locationId,
            "cssClass" => $this->cssClass,
        ];
    }

    private function deriveCssClass() {
        $cssClass = "editor-";
        switch ($this->color) {
            case PLAYER_COLOR_BROWN:
                return $cssClass .= "brown";
            case PLAYER_COLOR_SALMON:
                return $cssClass .= "salmon";
            case PLAYER_COLOR_TEAL:
                return $cssClass .= "teal";
            case PLAYER_COLOR_YELLOW:
                return $cssClass .= "yellow";
        }
    }
}
