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
            "SELECT editor_id id, editor_owner owner, editor_color color, editor_location location FROM editor";
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

    public function getNextActionSpaceForEditor($actionLocationCode) {
        $maxSpaces = $this->game->getGameStateValue(MAX_ACTION_SPACES);
        $editors = $this->getAllEditorsOnAnAction($actionLocationCode);
        if (count($editors) < $maxSpaces) {
            return $actionLocationCode + count($editors) + 1;
        }
        return 0;
    }

    public function getAllEditorsOnActionUiData($actionLocationCode) {
        $editors = $this->getAllEditorsOnAnAction($actionLocationCode);
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
        $playerColor = $player->getColor();
        $playerArea = LOCATION_PLAYER_AREA;
        $extraEditor = LOCATION_EXTRA_EDITOR;

        for ($i = 0; $i < 4; $i++) {
            $sql = "INSERT INTO editor (editor_owner, editor_color, editor_location) VALUES ($playerId, '$playerColor', $playerArea)";
            self::DbQuery($sql);
        }
        $sql = "INSERT INTO editor (editor_owner, editor_color, editor_location) VALUES ($playerId, '$playerColor', $extraEditor)";
        self::DbQuery($sql);
    }

    private function getAllEditorsOnAnAction($actionLocationCode) {
        $sql = "SELECT editor_id id, editor_owner owner, editor_color color, editor_location location FROM editor WHERE editor_location >= $actionLocationCode AND editor_location < $actionLocationCode + 6";
        $rows = self::getObjectListFromDB($sql);

        $editors = [];
        foreach ($rows as $row) {
            $editors[] = new AOCEditor($row);
        }
        return $editors;
    }
}
