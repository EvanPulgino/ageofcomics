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
 * AOCPlayerManager.class.php
 *
 * Player manager class
 *
 */

class AOCPlayerManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Setup players for a new game
     * @param array $players An array of players
     * @return void
     */
    public function setupNewGame($players) {
        $gameInfos = $this->game->getGameinfos();
        $defaultColors = $gameInfos["player_colors"];
        shuffle($defaultColors);
        $playerCount = count($players);
        $order = [];
        for ($i = 1; $i <= $playerCount; $i++) {
            $order[] = $i;
        }
        shuffle($order);

        $sql =
            "INSERT INTO player (player_id, player_no, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = [];
        foreach ($players as $playerId => $player) {
            $color = array_shift($defaultColors);
            $orderNum = array_shift($order);
            $values[] =
                "('" .
                $playerId .
                "','$orderNum','$color','" .
                $player["player_canal"] .
                "','" .
                addslashes($player["player_name"]) .
                "','" .
                addslashes($player["player_avatar"]) .
                "')";
        }

        $sql .= implode(",", $values);
        self::DbQuery($sql);
        $this->setTurnOrderToNaturalOrder();
        $this->game->reloadPlayersBasicInfos();
    }

    /**
     * Activate the next player in turn order
     * @return void
     */
    public function activateNextPlayer() {
        $playerCount = $this->getPlayerCount();
        $activePlayer = $this->getActivePlayer();
        if ($activePlayer->getTurnOrder() == $playerCount) {
            $nextPlayerId = $this->getPlayerIdByTurnOrder(1);
        } else {
            $nextPlayerId = $this->getPlayerIdByTurnOrder(
                $activePlayer->getTurnOrder() + 1
            );
        }

        $this->game->gamestate->changeActivePlayer($nextPlayerId);
    }

    /**
     * Adjust how many ideas a player has
     * @param int $playerId The player's ID
     * @param int $ideas The number of ideas to adjust by
     * @param string $type The type of ideas to adjust
     * @return void
     */
    public function adjustPlayerIdeas($playerId, $ideas, $type) {
        $sql =
            "UPDATE player SET player_" .
            $type .
            "_ideas = player_" .
            $type .
            "_ideas + $ideas WHERE player_id = $playerId";
        self::DbQuery($sql);
    }

    /**
     * Adjust how much money a player has
     * @param int $playerId The player's ID
     * @param int $money The amount of money to adjust by
     * @return void
     */
    public function adjustPlayerMoney($playerId, $money) {
        $sql = "UPDATE player SET player_money = player_money + $money WHERE player_id = $playerId";
        self::DbQuery($sql);
    }

    /**
     * Adjust how many points a player has
     * @param int $playerId The player's ID
     * @param int $score The number of points to adjust by
     * @return void
     */
    public function adjustPlayerScore($playerId, $score) {
        $sql = "UPDATE player SET player_score = player_score + $score WHERE player_id = $playerId";
        self::DbQuery($sql);
    }
    /**
     * Gets the active player as an AOCPlayer object
     * @return AOCPlayer The active player
     */
    public function getActivePlayer() {
        return $this->getPlayer($this->game->getActivePlayerId());
    }

    /**
     * Returns the number of players
     * @return int Number of players in the game
     */
    public function getPlayerCount() {
        return intval(
            self::getUniqueValueFromDB("SELECT COUNT(*) FROM player")
        );
    }

    /**
     * Gets an AOCPlayer object for the specified player ID
     * @param mixed $playerId
     * @return AOCPlayer Player object
     */
    public function getPlayer($playerId) {
        return $this->getPlayers([$playerId])[0];
    }

    /**
     * Gets the player ID for the specified turn order
     * @param int $turnOrder The turn order of the player
     * @return int The player ID
     */
    public function getPlayerIdByTurnOrder($turnOrder) {
        $sql = "SELECT player_id FROM player WHERE player_turn_order = $turnOrder";
        return intval(self::getUniqueValueFromDB($sql));
    }

    /**
     * Returns an array of AOCPlayer objects for all/specified player IDs
     * @param array<int> $playerIds An array of player IDs from database
     * @return array<AOCPlayer> An array of AOCPlayer objects
     */
    public function getPlayers($playerIds = null) {
        $sql =
            "SELECT player_id id, player_no naturalOrder, player_turn_order turnOrder, player_name name, player_color color, player_score score, player_score_aux scoreAux, player_money money, player_crime_ideas crimeIdeas, player_horror_ideas horrorIdeas, player_romance_ideas romanceIdeas, player_scifi_ideas scifiIdeas, player_superhero_ideas superheroIdeas, player_western_ideas westernIdeas, player_is_multiactive multiActive FROM player";
        if ($playerIds) {
            $sql .= " WHERE player_id IN (" . implode(",", $playerIds) . ")";
        }
        $rows = self::getObjectListFromDB($sql);

        $players = [];
        foreach ($rows as $row) {
            $players[] = new AOCPlayer($row);
        }
        return $players;
    }

    /**
     * Get ui data of all/specified players in a list
     * @param array<AOCPlayer> $players list of player objects
     * @return array
     */
    public function getPlayersUiData($playerIds = null) {
        $players = $this->getPlayers($playerIds);
        $uiData = [];
        foreach ($players as $player) {
            $uiData[] = $player->getUiData();
        }
        return $uiData;
    }

    /**
     * Syncs player turn order with randomly assigned natural order
     * This is only useful on game setup
     * @return void
     */
    private function setTurnOrderToNaturalOrder() {
        $sql =
            "UPDATE player SET player_turn_order = player_no WHERE player_no IS NOT NULL";
        self::DbQuery($sql);
    }
}
