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
 * Backend functions used by the performPrintUpgrade State
 *
 * @EvanPulgino
 */

class AOCPerformPrintGetUpgradeCubeState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    public function getArgs($playerId = null) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        return [
            "player" => $activePlayer->getUiData(),
        ];
    }

    public function stPerformPrintGetUpgradeCube() {
        $activePlayer = $this->game->playerManager->getActivePlayer();
        $printedComicCount = count(
            $this->game->cardManager->getPrintedComicsByPlayer(
                $activePlayer->getId()
            )
        );

        switch ($printedComicCount) {
            case 2:
                $this->game->gamestate->nextState("performPrintUpgrade");
                break;
            case 3:
                $this->game->gamestate->nextState("performPrintUpgrade");
                break;
            case 4:
                $this->game->gamestate->nextState("performPrintUpgrade");
                break;
            case 5:
                // Do nothing, requires player action
                break;
            default:
                $this->game->gamestate->nextState("continuePrint");
                break;
        }
    }

    public function selectUpgradeCube($cubeLocation) {
        $activePlayer = $this->game->playerManager->getActivePlayer();
        $cubeLocation = intval($cubeLocation);

        $this->game->setGameStateValue(UPGRADE_CUBE_TO_USE, $cubeLocation);

        $this->game->notifyAllPlayers(
            "upgradeCube",
            clienttranslate(
                '${player_name} has chosen to relocate their upgrade cube on the ${actionName} action.'
            ),
            [
                "player_name" => $activePlayer->getName(),
                "actionName" => $this->getActionName($cubeLocation),
            ]
        );

        $this->game->gamestate->nextState("performPrintUpgrade");
    }

    public function skipUpgrade() {
        $this->game->gamestate->nextState("continuePrint");
    }

    private function getActionName($actionKey) {
        return ucfirst(ACTION_STRING_FROM_KEY[$actionKey]);
    }
}
