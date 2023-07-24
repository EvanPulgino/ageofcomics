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

class AOCEditorManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Setup editors for a new game
     * @param AOCPlayer[] $players An array of players
     * @return void
     */
    public function setupNewGame(array $players) {
        foreach ($players as $player) {
            $this->createPlayerEditors($player);
        }
    }

    /**
     * Get all editors
     * @return AOCEditor[]
     */
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

    /**
     * Get uiData for all editors
     * @return array
     */
    public function getEditorsUiData() {
        $editors = $this->getEditors();
        $uiData = [];
        foreach ($editors as $editor) {
            $uiData[] = $editor->getUiData();
        }
        return $uiData;
    }

    /**
     * Move an editor to a new location
     * @param int $editorId - editor to move
     * @param int $newLocation - new location
     */
    public function moveEditor($editorId, $newLocation) {
        $sql = "UPDATE editor SET editor_location = $newLocation WHERE editor_id = $editorId";
        self::DbQuery($sql);
    }

    /**
     * Create editor meeple for a player
     * @param AOCPlayer $player
     * @return void
     */
    private function createPlayerEditors(AOCPlayer $player) {
        $playerId = $player->getId();
        $cssClass = $this->getEditorCssFromPlayerColor($player->getColor());
        $playerArea = LOCATION_PLAYER_AREA;
        $extraEditor = LOCATION_ACTION_SALES;

        for ($i = 0; $i < 4; $i++) {
            $sql = "INSERT INTO editor (editor_owner, editor_location, editor_class) VALUES ($playerId, $playerArea , '$cssClass')";
            self::DbQuery($sql);
        }
        $sql = "INSERT INTO editor (editor_owner, editor_location, editor_class) VALUES ($playerId, $extraEditor , '$cssClass')";
        self::DbQuery($sql);
    }

    /**
     * Get css class for editor based on player color
     * @param int $playerColor
     * @return string
     */
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
