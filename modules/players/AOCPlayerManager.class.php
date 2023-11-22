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
 * Player manager class, handles all player related logic
 *
 * @EvanPulgino
 */

class AOCPlayerManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Setup players for a new game
     * 
     * @param array $players An array of players
     * @return void
     */
    public function setupNewGame($players) {
        $gameInfos = $this->game->getGameinfos();
        $defaultColors = $gameInfos["player_colors"];
        shuffle($defaultColors);

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
        $this->setTurnOrderToNaturalOrder();
        $this->game->reloadPlayersBasicInfos();
    }

    /**
     * Activate the next player in turn order
     *
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
     *
     * @param int $playerId The player's ID
     * @param int $ideas The number of ideas to adjust by
     * @param string $genre The genre of ideas to adjust
     * @return void
     */
    public function adjustPlayerIdeas($playerId, $ideas, $genre) {
        $sql =
            "UPDATE player SET player_" .
            $genre .
            "_ideas = player_" .
            $genre .
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
     *
     * @param int $playerId The player's ID
     * @param int $score The number of points to adjust by
     * @return void
     */
    public function adjustPlayerScore($playerId, $score) {
        $sql = "UPDATE player SET player_score = player_score + $score WHERE player_id = $playerId";
        self::DbQuery($sql);
    }

    /**
     * A player gains an idea from hiring a value 1 creative
     *
     * @param AOCPlayer $player The player gaining the idea
     * @param AOCCard $card The creative card that was hired
     * @return void
     */
    public function gainIdeaFromHiringCreative($player, $card) {
        $this->adjustPlayerIdeas($player->getId(), 1, $card->getGenre());

        $this->game->notifyAllPlayers(
            "gainIdeaFromHiringCreative",
            clienttranslate(
                '${player_name} gains a ${genre} idea from hiring ${card_type_singular}'
            ),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "card" => $card->getUiData($player->getId()),
                "genre" => $card->getGenre(),
                "card_type_singular" =>
                    $card->getTypeId() == CARD_TYPE_ARTIST
                        ? "an artist"
                        : "a writer",
            ]
        );
    }

    /**
     * A player gains a starting idea
     *
     * @param int $playerId The player's ID
     * @param string $genre The genre of the idea to gain
     *
     * @return void
     */
    public function gainStartingIdea($playerId, $genre) {
        $this->adjustPlayerIdeas($playerId, 1, $genre);

        $this->game->notifyAllPlayers(
            "gainStartingIdea",
            clienttranslate('${player_name} gains a ${genre} idea'),
            [
                "player_name" => $this->game->playerManager
                    ->getPlayer($playerId)
                    ->getName(),
                "player_id" => $playerId,
                "genre" => $genre,
            ]
        );
    }

    /**
     * Gets the active player as an AOCPlayer object
     *
     * @return AOCPlayer The active player
     */
    public function getActivePlayer() {
        return $this->getPlayer($this->game->getActivePlayerId());
    }

    /**
     * Gets an AOCPlayer object for the specified player ID
     *
     * @param mixed $playerId
     * @return AOCPlayer Player object
     */
    public function getPlayer($playerId) {
        return $this->getPlayers([$playerId])[0];
    }

    /**
     * Returns the number of players
     *
     * @return int Number of players in the game
     */
    public function getPlayerCount() {
        return intval(
            self::getUniqueValueFromDB("SELECT COUNT(*) FROM player")
        );
    }

    /**
     * Gets the player ID for the specified turn order
     *
     * @param int $turnOrder The turn order of the player
     * @return int The player ID
     */
    public function getPlayerIdByTurnOrder($turnOrder) {
        $sql = "SELECT player_id FROM player WHERE player_turn_order = $turnOrder";
        return intval(self::getUniqueValueFromDB($sql));
    }

    /**
     * Returns an array of AOCPlayer objects for all/specified player IDs
     *
     * @param array<int> $playerIds An array of player IDs
     * @return array<AOCPlayer> An array of AOCPlayer objects
     */
    public function getPlayers($playerIds = null) {
        $sql =
            "SELECT player_id id, player_no naturalOrder, player_turn_order turnOrder, player_name name, player_color color, player_score score, player_score_aux scoreAux, player_money money, player_crime_ideas crimeIdeas, player_horror_ideas horrorIdeas, player_romance_ideas romanceIdeas, player_scifi_ideas scifiIdeas, player_superhero_ideas superheroIdeas, player_western_ideas westernIdeas, player_agent_location agentLocation, player_cube_one_location cubeOneLocation, player_cube_two_location cubeTwoLocation, player_cube_three_location cubeThreeLocation, player_is_multiactive multiActive FROM player";
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
     * Returns an array of AOCPlayer objects for all players in turn order
     *
     * @return array<AOCPlayer> An array of AOCPlayer objects
     */
    public function getPlayersInViewOrder() {
        $players = $this->getPlayers();
        $playerCount = count($players);
        $currentPlayer = self::findPlayerById(
            $players,
            $this->game->getViewingPlayerId()
        );

        if ($currentPlayer) {
            $sortedPlayers = [];
            $sortedPlayers[] = $currentPlayer;
            $lastPlayerAdded = $currentPlayer;

            while (count($sortedPlayers) < $playerCount) {
                $nextPlayerNaturalOrder = 0;
                if ($lastPlayerAdded->getNaturalOrder() == $playerCount) {
                    $nextPlayerNaturalOrder = 1;
                } else {
                    $nextPlayerNaturalOrder =
                        $lastPlayerAdded->getNaturalOrder() + 1;
                }

                $nextPlayer = self::findPlayerByNaturalOrder(
                    $players,
                    $nextPlayerNaturalOrder
                );
                $sortedPlayers[] = $nextPlayer;
                $lastPlayerAdded = $nextPlayer;
            }

            return $sortedPlayers;
        } else {
            return $players;
        }
    }

    /**
     * Get all/specified players in a list formatted for the UI
     *
     * @param array<AOCPlayer> $players List of player objects
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
     * A player gains money from the Royalties action
     *
     * @param AOCPlayer $player The player gaining the money
     * @param int $actionSpace The ID of the Royalties action space
     * @return void
     */
    public function gainRoyalties($player, $actionSpace) {
        $amount = ROYALTIES_AMOUNTS[$actionSpace];
        $this->adjustPlayerMoney($player->getId(), $amount);

        $this->game->notifyAllPlayers(
            "takeRoyalties",
            clienttranslate('${player_name} takes royalties of $${amount}'),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "amount" => $amount,
            ]
        );
    }

    /**
     * Checks if a player is the last player in turn order
     *
     * @param AOCPlayer $player The player to check
     * @return bool True if the player is last in turn order, false otherwise
     */
    public function isLastPlayerInTurnOrder($player) {
        $playerCount = $this->getPlayerCount();
        return $player->getTurnOrder() == $playerCount;
    }

    /**
     * Gets a player object from an ID. This is needed in case the viewing player is a spectator
     *
     * @param AOCPlayer[] $players An array of player objects playing the game
     * @param int $playerId The player's ID
     * @return AOCPlayer|bool The player object or false if not found
     */
    private function findPlayerById($players, $playerId) {
        foreach ($players as $player) {
            if ($playerId == $player->getId()) {
                return $player;
            }
        }

        return false;
    }

    /**
     * Gets a player object based on their natural order.
     * Natural order is set at the begining of the game and never changes (unlike turn order)
     *
     * @param AOCPlayer[] $players An array of player objects playing the game
     * @param int $naturalOrder The player's natural order
     * @return AOCPlayer|bool The player object or false if not found
     */
    private function findPlayerByNaturalOrder($players, $naturalOrder) {
        foreach ($players as $player) {
            if ($naturalOrder == $player->getNaturalOrder()) {
                return $player;
            }
        }

        return false;
    }

    /**
     * Syncs player turn order with randomly assigned natural order
     * This is only useful on game setup
     * 
     * @return void
     */
    private function setTurnOrderToNaturalOrder() {
        $sql =
            "UPDATE player SET player_turn_order = player_no WHERE player_no IS NOT NULL";
        self::DbQuery($sql);
    }
}
