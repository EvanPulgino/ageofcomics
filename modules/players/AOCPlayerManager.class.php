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

require_once "AOCPlayer.class.php";
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

        $sql =
            "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = [];
        foreach ($players as $playerId => $player) {
            $color = array_shift($defaultColors);
            $values[] =
                "('" .
                $playerId .
                "','$color','" .
                $player["player_canal"] .
                "','" .
                addslashes($player["player_name"]) .
                "','" .
                addslashes($player["player_avatar"]) .
                "')";
        }

        $sql .= implode(",", $values);
        self::DbQuery($sql);
        $this->game->reattributeColorsBasedOnPreferences(
            $players,
            $gameInfos["player_colors"]
        );
        $this->game->reloadPlayersBasicInfos();
    }

    /**
     * Returns an array of AOCPlayer objects for all/specified player IDs
     * @param array<int> $playerIds An array of player IDs from database
     * @return array<AOCPlayer> An array of AOCPlayer objects
     */
    public function getPlayers($playerIds = null) {
        $query =
            "SELECT player_id id, player_no naturalOrder, player_name name, player_color color, player_score score, player_score_aux scoreAux, player_money money, player_crime_ideas crimeIdeas, player_horror_ideas horrorIdeas, player_romance_ideas romanceIdeas, player_scifi_ideas scifiIdeas, player_superhero_ideas superheroIdeas, player_western_ideas westernIdeas, player_is_multiactive multiActive FROM player";
        if ($playerIds) {
            $query .= " WHERE player_id IN (" . implode(",", $playerIds) . ")";
        }
        $rows = self::getObjectListFromDB($query);

        $players = [];
        foreach ($rows as $row) {
            $players[] = new AOCPlayer($this->game, $row);
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
}
