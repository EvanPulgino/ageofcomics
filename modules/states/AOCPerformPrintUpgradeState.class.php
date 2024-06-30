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

class AOCPerformPrintUpgradeState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    public function getArgs($playerId = null) {
        $activePlayer = $this->game->playerManager->getActivePlayer();
        $printedComicCount = $this->printedComicCount($activePlayer);
        $upgradableActions = [];

        if ($printedComicCount == 2) {
            $upgradableActions[] = HIRE_ACTION;
            $upgradableActions[] = DEVELOP_ACTION;
            $upgradableActions[] = IDEAS_ACTION;
        }

        if ($printedComicCount == 3) {
            $upgradableActions[] = HIRE_ACTION;
            $upgradableActions[] = DEVELOP_ACTION;
            $upgradableActions[] = IDEAS_ACTION;
            $upgradableActions[] = PRINT_ACTION;
        }

        if ($printedComicCount == 4 || $printedComicCount == 5) {
            $upgradableActions[] = HIRE_ACTION;
            $upgradableActions[] = DEVELOP_ACTION;
            $upgradableActions[] = IDEAS_ACTION;
            $upgradableActions[] = PRINT_ACTION;
            $upgradableActions[] = ROYALTIES_ACTION;
            $upgradableActions[] = SALES_ACTION;
        }

        $playerCubes = [
            $activePlayer->getCubeOneLocation(),
            $activePlayer->getCubeTwoLocation(),
            $activePlayer->getCubeThreeLocation(),
        ];

        $upgradableActions = array_filter($upgradableActions, function (
            $action
        ) use ($playerCubes) {
            return !in_array($action, $playerCubes);
        });

        $upgradeCubeToUse = $this->game->getGameStateValue(UPGRADE_CUBE_TO_USE);

        return [
            "player" => $activePlayer->getUiData(),
            "upgradableActions" => $upgradableActions,
            "upgradeCubeToUse" => $upgradeCubeToUse,
        ];
    }

    public function placeUpgradeCube($actionKey, $cubeMoved) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Reset the upgrade cube to use variable
        $this->game->setGameStateValue(UPGRADE_CUBE_TO_USE, 0);

        switch ($cubeMoved) {
            case 1:
                $activePlayer->setCubeOneLocation($actionKey);
                break;
            case 2:
                $activePlayer->setCubeTwoLocation($actionKey);
                break;
            case 3:
                $activePlayer->setCubeThreeLocation($actionKey);
                break;
        }

        $this->game->playerManager->savePlayer($activePlayer);

        $this->game->notifyAllPlayers(
            "placeUpgradeCube",
            clienttranslate(
                '${player_name} places a cube to upgrade ${action_name}'
            ),
            [
                "player_name" => $activePlayer->getName(),
                "player" => $activePlayer->getUiData(),
                "action_name" => ucfirst(ACTION_STRING_FROM_KEY[$actionKey]),
                "actionKey" => $actionKey,
                "cubeMoved" => $cubeMoved,
            ]
        );

        $this->game->gamestate->nextState("continuePrint");
    }

    /**
     * Get the number of printed comics the player has in the genre
     *
     * @param AOCPlayer $player The player to check
     * @param int $genreId The genre to check
     * @return int The number of printed comics the player has in the genre
     */
    private function printedComicCount($player) {
        $printedOriginals = $this->game->cardManager->getCards(
            CARD_TYPE_COMIC,
            null,
            LOCATION_PLAYER_MAT,
            $player->getId()
        );
        $printedRipoffs = $this->game->cardManager->getCards(
            CARD_TYPE_RIPOFF,
            null,
            LOCATION_PLAYER_MAT,
            $player->getId()
        );

        return count($printedOriginals) + count($printedRipoffs);
    }
}
