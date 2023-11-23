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
 * Backend functions used by the nextPlayerSetup State
 *
 * During this game state the number of ideas and extra money a player gets is determined based on their starting player order.
 *
 * @EvanPulgino
 */

class AOCNextPlayerSetupState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the NextPlayerSetup state
     *
     * Args:
     *  - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * This is called upon entering the nextPlayerSetup state.
     * It sets how many ideas and how much extra money a player gets based on their starting player order.
     *
     * @return void
     */
    public function stNextPlayerSetup() {
        // Get the active player
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // If the active player is the last player in turn order, end player setup
        if (
            $this->game->playerManager->isLastPlayerInTurnOrder($activePlayer)
        ) {
            $this->game->gamestate->nextState("endPlayerSetup");
        } else {
            // Otherwise, activate the next player and set the number of ideas and extra money they get
            $this->game->playerManager->activateNextPlayer();
            $activePlayer = $this->game->playerManager->getActivePlayer();
            switch ($activePlayer->getTurnOrder()) {
                case 1:
                    // First player gets 2 ideas
                    $this->game->setGameStateValue(START_IDEAS, 2);
                    break;
                case 2:
                    // Second player gets 3 ideas
                    $this->game->setGameStateValue(START_IDEAS, 3);
                    break;
                case 3:
                    // Third player gets 2 ideas and 1 extra money
                    $this->game->setGameStateValue(START_IDEAS, 2);
                    $this->game->playerManager->adjustPlayerMoney(
                        $activePlayer->getId(),
                        1
                    );

                    // Notify all players of the extra money
                    $this->game->notifyAllPlayers(
                        "setupMoney",
                        clienttranslate(
                            '${player_name} gets 1 money for being third in turn order'
                        ),
                        [
                            "player_name" => $activePlayer->getName(),
                            "player" => $activePlayer->getUiData(),
                            "money" => 1,
                        ]
                    );
                    break;
                case 4:
                    // Fourth player gets 3 ideas and 1 extra money
                    $this->game->setGameStateValue(START_IDEAS, 3);
                    $this->game->playerManager->adjustPlayerMoney(
                        $activePlayer->getId(),
                        1
                    );

                    // Notify all players of the extra money
                    $this->game->notifyAllPlayers(
                        "setupMoney",
                        clienttranslate(
                            '${player_name} gets 1 money for being fourth in turn order'
                        ),
                        [
                            "player_name" => $activePlayer->getName(),
                            "player" => $activePlayer->getUiData(),
                            "money" => 1,
                        ]
                    );
                    break;
            }

            // Go to player setup state for the next player
            $this->game->gamestate->nextState("nextPlayerSetup");
        }
    }
}
