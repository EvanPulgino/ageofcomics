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
 * Backend functions used by the roundEndEstablishPlayerOrder State
 *
 * This state handles the establish player order step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndEstablishPlayerOrderState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndEstablishPOlayerOrder state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * Establish player order for the next round
     * Rank players by their comic with the most fans
     * Set the new turn order in reverse order of rank
     * If players are tied, their relative turn order is reversed
     *
     * @return void
     */
    public function stRoundEndEstablishPlayerOrder() {
        // Get the player rankings
        $playerRankings = $this->getPlayerRankings();

        // Create a map where number of fans is the key and the value is an array of player IDs
        $playerRankingsMap = [];
        foreach ($playerRankings as $playerId => $fans) {
            if (!array_key_exists($fans, $playerRankingsMap)) {
                $playerRankingsMap[$fans] = [];
            }
            $playerRankingsMap[$fans][] = $playerId;
        }

        // Sort map by least to most fans
        ksort($playerRankingsMap);

        // Initialize variable to hold current turn order being assigned
        $currentTurnOrder = 1;

        // Initialize array to hold players ui data in new order -- to pass to frontend
        $newTurnOrder = [];

        // Iterate through the player rankings map
        foreach ($playerRankingsMap as $fans => $playerIds) {
            // If there is only one player with this number of fans, assign them the current turn order
            if (count($playerIds) == 1) {
                $playerId = $playerIds[0];
                $player = $this->game->playerManager->getPlayer($playerId);
                $this->game->playerManager->setNewTurnOrder(
                    $player,
                    $currentTurnOrder
                );
                $newTurnOrder[] = $player->getUiData();
                $currentTurnOrder++;
            } else {
                // Initialize array to hold the turn order being assigned to tied players
                $currentTurnOrderForTiedPlayers = [];
                foreach ($playerIds as $playerId) {
                    // Get the player
                    $player = $this->game->playerManager->getPlayer($playerId);
                    // Put the player in the array
                    $currentTurnOrderForTiedPlayers[] = $player;
                }
                // Sort the tied players by existing turn order from last round from last to first
                usort($currentTurnOrderForTiedPlayers, function ($a, $b) {
                    return $b->getTurnOrder() - $a->getTurnOrder();
                });

                // Iterate through the tied players and assign them the current turn order
                foreach ($currentTurnOrderForTiedPlayers as $player) {
                    $this->game->playerManager->setNewTurnOrder(
                        $player,
                        $currentTurnOrder
                    );
                    $newTurnOrder[] = $player->getUiData();
                    $currentTurnOrder++;
                }
            }
        }

        // Notify players of the new turn order
        $this->game->notifyAllPlayers(
            "newTurnOrder",
            clienttranslate("The new turn order has been established"),
            ["newTurnOrder" => $newTurnOrder]
        );

        // Go to next state
        $this->game->gamestate->nextState("subtractFans");
    }

    private function getPlayerRankings() {
        $players = $this->game->playerManager->getPlayers();
        $playerRankings = [];

        foreach ($players as $playerKey => $player) {
            // Get the number of fans of the top performing comic
            $topComicFans = $this->getTopPerformingComicFans($player->getId());
            $playerRankings[$player->getId()] = $topComicFans;
        }

        return $playerRankings;
    }

    /**
     * Returns the number of fans of the top performing comic of a player
     *
     * @param int $playerId The ID of the player
     * @return int The number of fans of the top performing comic
     */
    private function getTopPerformingComicFans($playerId) {
        $playerComics = $this->game->miniComicManager->getMiniComicsByPlayer(
            $playerId
        );
        //If the player has no comics, return 0
        if (count($playerComics) == 0) {
            return 0;
        }

        // Initialize variable to hold top comic fans
        $topComicFans = 0;

        foreach ($playerComics as $comic) {
            $comicFans = $comic->getFans();
            if ($comicFans > $topComicFans) {
                $topComicFans = $comicFans;
            }
        }

        return $topComicFans;
    }
}
