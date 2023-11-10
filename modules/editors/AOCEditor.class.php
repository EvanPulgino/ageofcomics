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
 * Object class for an Editor meeple.
 * Contains:
 * - The database ID of the editor meeple
 * - The ID of the player who owns the editor meeple
 * - The color of the editor meeple
 * - The ID of the location the editor meeple is on
 * - The CSS class for the editor meeple
 *
 * Editor meeples are player workers. Each turn a player places one of their editor meeples on an action space to take that action.
 * Once a player has placed all of their Editor meeples, they must pass and are finished taking actions for the round.
 * 
 * Editor meeples are represented by the "editor" table in the database.
 *
 * A player starts with access to 4 Editor meeples. If a player uses the Special Action of the'Sales' action, they will gain a 5th Editor meeple to use in that round.
 *
 * At the end of the round, all Editor meeples are returned to their owners. If a player has used the Editor from Special Action of the 'Sales' action, the meeple is returned to the board.
 *
 * @EvanPulgino
 */

class AOCEditor {
    /**
     * @var int $id The database ID of the editor meeple
     */
    private $id;

    /**
     * @var int $playerId The ID of the player who owns the editor meeple
     */
    private $playerId;

    /**
     * @var string $color The color of the editor meeple
     */
    private $color;

    /**
     * @var int $locationId The ID of the location the editor meeple is on:
     * - 3 = In player's area
     * - 8 = In the extra editor area of the board
     * - 10001/10002/10003/10004/10005 = An action space of the Hire action
     * - 20001/20002/20003/20004/20005 = An action space of the Develop action
     * - 30001/30002/30003/30004/30005 = An action space of the Ideas action
     * - 40001/40002/40003/40004/40005 = An action space of the Print action
     * - 50001/50002/50003/50004/50005 = An action space of the Royalties action
     * - 60001/60002/60003/60004/60005 = An action space of the Sell action
     */
    private $locationId;

    /**
     * @var string $cssClass The CSS class of the editor meeple
     */
    private $cssClass;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->playerId = (int) $row["owner"];
        $this->color = $row["color"];
        $this->locationId = (int) $row["location"];
        $this->cssClass = $this->deriveCssClass();
    }

    /**
     * Get the database ID of the editor meeple
     *
     * @return int The database ID of the editor meeple
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the ID of the player who owns the editor meeple
     *
     * @return int The ID of the player who owns the editor meeple
     */
    public function getPlayerId() {
        return $this->playerId;
    }

    /**
     * Get the color of the editor meeple
     *
     * @return string The color of the editor meeple
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * Get the ID of the location the editor meeple is on:
     * - 3 = In player's area
     * - 8 = In the extra editor area of the board
     * - 10001/10002/10003/10004/10005 = An action space of the Hire action
     * - 20001/20002/20003/20004/20005 = An action space of the Develop action
     * - 30001/30002/30003/30004/30005 = An action space of the Ideas action
     * - 40001/40002/40003/40004/40005 = An action space of the Print action
     * - 50001/50002/50003/50004/50005 = An action space of the Royalties action
     * - 60001/60002/60003/60004/60005 = An action space of the Sell action
     *
     * @return int The ID of the location the editor meeple is on
     */
    public function getLocationId() {
        return $this->locationId;
    }

    /**
     * Set the ID of the location the editor meeple is on
     *
     * @param int $locationId The ID of the location the editor meeple is on
     * @return void
     */
    public function setLocationId($locationId) {
        $this->locationId = $locationId;
    }

    /**
     * Get the CSS class of the editor meeple
     *
     * @return string The CSS class of the editor meeple
     */
    public function getCssClass() {
        return $this->cssClass;
    }

    /**
     * Get the data needed to render the editor meeple on the UI
     */
    public function getUiData() {
        return [
            "id" => $this->id,
            "playerId" => $this->playerId,
            "color" => $this->color,
            "locationId" => $this->locationId,
            "cssClass" => $this->cssClass,
        ];
    }

    /**
     * Derive the CSS class for the editor meeple based on the player color
     */
    private function deriveCssClass() {
        $cssClass = "aoc-editor-";
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
