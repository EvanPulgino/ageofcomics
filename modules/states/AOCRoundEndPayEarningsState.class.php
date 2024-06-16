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
 * Backend functions used by the roundEndPayEarnings State
 *
 * This state handles the pay earnings step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndPayEarningsState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndPayEarnings state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * Pay each player their earnings for the round
     *
     * @return void
     */
    public function stRoundEndPayEarnings() {
        // Get the list of players
        $players = $this->game->playerManager->getPlayers();

        // Sort in ascending turn order
        usort($players, function ($a, $b) {
            return $a->getTurnOrder() - $b->getTurnOrder();
        });

        // Iterate through each player
        foreach ($players as $player) {
            // Get the player's income
            $income = $player->getIncome();

            // Pay the player their income
            $this->game->playerManager->adjustPlayerMoney($player, $income);

            // Notify the player of their earnings
            $this->game->notifyAllPlayers(
                "adjustMoney",
                clienttranslate('${player_name} earns ${amount} money from their printed comics'),
                [
                    "player_name" => $player->getName(),
                    "player" => $player->getUiData(),
                    "amount" => $income,
                ]
            );
        }

        // If the game has reached the end of the final round, end the game here
        // Otherwise, move to the next state
        if ($this->game->getGameStateValue(CURRENT_ROUND) === 5) {
            $this->game->gamestate->nextState("endGame");
        } else {
            $this->game->gamestate->nextState("establishPlayerOrder");
        }
    }
}
