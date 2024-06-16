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
 * Backend functions used by the roundEndEstablishRanking State
 *
 * This state handles the establish ranking step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndEstablishRankingState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndEstablishRanking state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * Establish ranking of players based on their top performing comic
     *
     * @return void
     */
    public function stRoundEndEstablishRanking() {
        // Get the player rankings
        $playerRankings = $this->getOrderedPlayerRankings();

        $currentAwardLevel = 1;
        $currentFansForAwardLevel = 0;

        // Iterate through the player rankings
        foreach ($playerRankings as $playerId => $fans) {
            // If current fans for award is zero, set it to the fans of the current player
            if ($currentFansForAwardLevel == 0) {
                $currentFansForAwardLevel = $fans;
            } else {
                // If the fans of the current player are less than the current fans for award level,
                // set the current fans for award level to the fans of the current player ands go to the next award level
                if ($fans < $currentFansForAwardLevel) {
                    $currentFansForAwardLevel = $fans;
                    $currentAwardLevel++;
                }
            }

            // Award VP for the current award level
            $this->awardVPForLevel($currentAwardLevel, $playerId);
        }

        // Move to the next state
        $this->game->gamestate->nextState("payEarnings");
    }

    /**
     * Award VP to a player for a given award level
     *
     * @param int $awardLevel The award level to award stars for (1, 2, and 3 for 1st, 2nd, and 3rd place)
     * @param int $playerId The ID of the player to award stars to
     */
    private function awardVPForLevel($awardLevel, $playerId) {
        // If award level is greater than 3, return
        if ($awardLevel > 3) {
            return;
        }
        $player = $this->game->playerManager->getPlayer($playerId);
        $pointsEarned = 0;
        $placeString = "";

        // Determine the number of points earned based on the award level and set the place string
        switch ($awardLevel) {
            case 1:
                $pointsEarned = 3;
                $placeString = clienttranslate("1st");
                break;
            case 2:
                $pointsEarned = 2;
                $placeString = clienttranslate("2nd");
                break;
            case 3:
                $pointsEarned = 1;
                $placeString = clienttranslate("3rd");
                break;
            default:
                break;
        }

        // Adjust player's score
        $this->game->playerManager->adjustPlayerScore($player, $pointsEarned);

        // Notify players
        $this->game->notifyAllPlayers(
            "adjustScore",
            clienttranslate(
                '${player_name} has earned ${scoreChange} VP for having the ${placeString} ranked comic'
            ),
            [
                "player_name" => $player->getName(),
                "player" => $player->getUiData(),
                "scoreChange" => $pointsEarned,
                "placeString" => $placeString,
            ]
        );
    }

    private function getOrderedPlayerRankings() {
        $players = $this->game->playerManager->getPlayers();
        $playerRankings = [];

        foreach ($players as $playerKey => $player) {
            // Get the number of fans of the top performing comic
            $topComicFans = $this->getTopPerformingComicFans($player->getId());
            // Only include players with fans
            if ($topComicFans > 0) {
                $playerRankings[$player->getId()] = $topComicFans;
            }
        }

        // Sort player rankings by fans
        arsort($playerRankings);

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
