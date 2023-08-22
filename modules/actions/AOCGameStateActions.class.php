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
 * AOCGameStateActions.php
 *
 * All actions initiated by the game
 *
 */

class AOCGameStateActions {
    private $game;
    public function __construct($game) {
        $this->game = $game;
    }

    function stPlayerSetup() {
        $activePlayer = $this->game->playerManager->getActivePlayer();
        switch ($activePlayer->getTurnOrder()) {
            case 1:
                $this->game->setGameStateValue(START_IDEAS, 2);
                break;
            case 2:
                $this->game->setGameStateValue(START_IDEAS, 3);
                break;
            case 3:
                $this->game->setGameStateValue(START_IDEAS, 2);
                $this->game->playerManager->adjustPlayerMoney(
                    $activePlayer->getId(),
                    1
                );
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
                $this->game->setGameStateValue(START_IDEAS, 3);
                $this->game->playerManager->adjustPlayerMoney(
                    $activePlayer->getId(),
                    1
                );
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
    }
}
