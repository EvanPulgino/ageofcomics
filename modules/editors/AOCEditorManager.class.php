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
 * Editor meeple manager class, handles all editor meeple related logic
 *
 */

class AOCEditorManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Setup editors for a new game
     *
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
     *
     * @return AOCEditor[] Array of all editors
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
     *
     * @return array Array of all editors uiData
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
     * Gets the next placeable action space for an action
     *
     * @param int $actionLocationCode - the location code of the action
     * @return int - the location code of the next placeable action space
     */
    public function getNextActionSpaceForEditor($actionLocationCode) {
        $maxSpaces = $this->game->getGameStateValue(MAX_ACTION_SPACES);
        $editors = $this->getAllEditorsOnAnAction($actionLocationCode);
        if (count($editors) < $maxSpaces) {
            return $actionLocationCode + count($editors) + 1;
        }
        return 0;
    }

    /**
     * Gets the uiData of all editors on an action
     *
     * @param int $actionLocationCode - the location code of the action
     * @return array - the uiData of all editors on the action
     */
    public function getAllEditorsOnActionUiData($actionLocationCode) {
        $editors = $this->getAllEditorsOnAnAction($actionLocationCode);
        $uiData = [];
        foreach ($editors as $editor) {
            $uiData[] = $editor->getUiData();
        }
        return $uiData;
    }

    /**
     * Get a count of the number of editors a player has in their player area
     *
     * @param int $playerId - the ID of the player
     * @return int - the number of editors the player has in their player area
     */
    public function getPlayerRemainingEditorsCount($playerId) {
        $sql =
            "SELECT COUNT(*) FROM editor WHERE editor_owner = $playerId AND editor_location = " .
            LOCATION_PLAYER_AREA;
        return self::getUniqueValueFromDB($sql);
    }

    /**
     * Get a count of the number of editors remaining in all player areas
     *
     * @return int - the number of editors remaining in all player area
     */
    public function getAllRemainingEditorsCount() {
        $sql =
            "SELECT COUNT(*) FROM editor WHERE editor_location = " .
            LOCATION_PLAYER_AREA;
        return self::getUniqueValueFromDB($sql);
    }

    /**
     * Move an editor to a new location
     *
     * @param int $editorId - editor to move
     * @param int $newLocation - new location
     *
     * @return void
     */
    public function moveEditor($editorId, $newLocation) {
        $sql = "UPDATE editor SET editor_location = $newLocation WHERE editor_id = $editorId";
        self::DbQuery($sql);
    }

    /**
     * Move an editor from a player's player area to an action space
     *
     * @param int $playerId - player to move editor from
     * @param int $actionSpace - action space to move editor to
     *
     * @return AOCEditor - the editor that was moved
     */
    public function movePlayerEditorToActionSpace($playerId, $actionSpace) {
        $editor = $this->getOnePlayerEditorFromPlayerArea($playerId);
        $this->moveEditor(
            $this->getOnePlayerEditorFromPlayerArea($playerId)->getId(),
            $actionSpace
        );
        return $editor;
    }

    /**
     * Create editor meeples for a player
     *
     * @param AOCPlayer $player - the player to create editors for
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

    /**
     * Get all editors on an action
     *
     * @param int $actionLocationCode - the location code of the action
     * @return AOCEditor[] - all editors on the action
     */
    private function getAllEditorsOnAnAction($actionLocationCode) {
        $sql = "SELECT editor_id id, editor_owner owner, editor_color color, editor_location location FROM editor WHERE editor_location >= $actionLocationCode AND editor_location < $actionLocationCode + 6";
        $rows = self::getObjectListFromDB($sql);

        $editors = [];
        foreach ($rows as $row) {
            $editors[] = new AOCEditor($row);
        }
        return $editors;
    }

    /**
     * Get the first editor in a player's player area
     *
     * @param int $playerId - the ID of the player
     * @return AOCEditor - the first editor in the player's player area
     */
    private function getOnePlayerEditorFromPlayerArea($playerId) {
        $sql =
            "SELECT editor_id id, editor_owner owner, editor_color color, editor_location location FROM editor WHERE editor_owner = $playerId AND editor_location = " .
            LOCATION_PLAYER_AREA;
        $row = self::getObjectListFromDB($sql);

        return new AOCEditor($row[0]);
    }
}
