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
 * AOCEditorManager.class.php
 *
 * Editor manager class
 *
 */

require_once "AOCEditor.class.php";
require_once __DIR__ . "/../players/AOCPlayer.class.php";
class AOCEditorManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    public function setupNewGame(array $players) {
        foreach ($players as $player) {
            $this->createPlayerEditors($player);
        }
    }

    public function getEditors() {
        $sql =
            "SELECT editor_id id, editor_owner owner, editor_location location, editor_class class FROM editor";
        $rows = self::getObjectListFromDB($sql);

        $editors = [];
        foreach ($rows as $row) {
            $editors[] = new AOCEditor($row);
        }
        return $editors;
    }

    public function getEditorsUiData() {
        $editors = $this->getEditors();
        $uiData = [];
        foreach ($editors as $editor) {
            $uiData[] = $editor->getUiData();
        }
        return $uiData;
    }

    private function createPlayerEditors(AOCPlayer $player) {
        $playerId = $player->getId();
        $cssClass = $this->getEditorCssFromPlayerColor($player->getColor());
        $playerArea = LOCATION_PLAYER;
        $extraEditor = LOCATION_EXTRA_EDITOR;

        for ($i = 0; $i < 4; $i++) {
            $sql = "INSERT INTO editor (editor_owner, editor_location, editor_class) VALUES ($playerId, $playerArea , '$cssClass')";
            self::DbQuery($sql);
        }
        $sql = "INSERT INTO editor (editor_owner, editor_location, editor_class) VALUES ($playerId, $extraEditor , '$cssClass')";
        self::DbQuery($sql);
    }

    private function getEditorCssFromPlayerColor($playerColor) {
        switch ($playerColor) {
            case PLAYER_COLOR_BROWN:
                return "editor-brown";
            case PLAYER_COLOR_SALMON:
                return "editor-salmon";
            case PLAYER_COLOR_TEAL:
                return "editor-teal";
            case PLAYER_COLOR_YELLOW:
                return "editor-yellow";
            default:
                throw new BgaVisibleSystemException(
                    "Invalid player color: $playerColor"
                );
        }
    }
}
