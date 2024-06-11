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
 * Backend functions used by the performSales State
 *
 * @EvanPulgino
 */

class AOCPerformSalesState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the PerformSales state
     *
     * Args:
     * - selectedActionSpace => The id of the action space where the player placed their editor
     * - hasWalked => Whether the player has already used their free walk action
     * - numberOfActions => The number of sales order flip and collect actions the player can take
     * - tickets => The number of Super-transport tickets the player has
     * - saleAgentLocation => The location of the sale agent
     *
     * @return array The list of args used by the PerformPrint state
     */
    public function getArgs() {
        $activePlayer = $this->game->playerManager->getActivePlayer();
        $selectedActionSpace = $this->game->getGameStateValue(
            SELECTED_ACTION_SPACE
        );
        $hasWalked =
            $this->game->getGameStateValue(HAS_WALKED) == "1" ? true : false;
        $this->setNumberOfActions();

        return [
            "selectedActionSpace" => $selectedActionSpace,
            "hasWalked" => $hasWalked,
            "remainingCollectActions" => $this->game->getGameStateValue(
                SALES_ORDER_COLLECTS_REMAINING
            ),
            "remainingFlipActions" => $this->game->getGameStateValue(
                SALES_ORDER_FLIPS_REMAINING
            ),
            "tickets" => intval($activePlayer->getTickets()),
            "salesAgentLocation" => intval($activePlayer->getAgentLocation()),
            "playerMoney" => intval($activePlayer->getMoney()),
        ];
    }

    public function moveSalesAgent($space) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Set new location of the sales agent
        $this->game->playerManager->movePlayerSalesAgent($activePlayer, $space);

        $hasWalked = $this->game->getGameStateValue(HAS_WALKED);

        if ($hasWalked == 0) {
            // If player hasn't walked yet, record it
            $this->game->setGameStateValue(HAS_WALKED, 1);

            $this->game->notifyAllPlayers(
                "playerWalked",
                clienttranslate(
                    '${player_name} moves their sales agent one space'
                ),
                [
                    "player" => $this->game->playerManager
                        ->getActivePlayer()
                        ->getUiData(),
                    "player_name" => $this->game->playerManager
                        ->getActivePlayer()
                        ->getName(),
                    "space" => $space,
                ]
            );
        } else {
            // If player has already walked, spend 2 money
            $this->game->playerManager->adjustPlayerMoney($activePlayer, -2);

            $this->game->notifyAllPlayers(
                "playerUsedTaxi",
                clienttranslate(
                    '${player_name} pays 2 money to move their sales agent one space'
                ),
                [
                    "player" => $this->game->playerManager
                        ->getActivePlayer()
                        ->getUiData(),
                    "player_name" => $this->game->playerManager
                        ->getActivePlayer()
                        ->getName(),
                    "space" => $space,
                    "moneyAdjustment" => -2,
                ]
            );
        }

        // Re-enter sales action state
        $this->game->gamestate->nextState("continueSales");
    }

    private function setNumberOfActions() {
        if (
            $this->game->getGameStateValue(SALES_ORDER_COLLECTS_REMAINING) ==
                -1 &&
            $this->game->getGameStateValue(SALES_ORDER_FLIPS_REMAINING) == -1
        ) {
            $selectedActionSpace = $this->game->getGameStateValue(
                SELECTED_ACTION_SPACE
            );

            $numberOfActions = 0;

            switch ($selectedActionSpace) {
                case 60001:
                    $numberOfActions = 3;
                    break;
                case 60002:
                    $numberOfActions = 2;
                    break;
                case 60003:
                    $numberOfActions = 2;
                    break;
                default:
                    $numberOfActions = 1;
                    break;
            }

            $this->game->setGameStateValue(
                SALES_ORDER_COLLECTS_REMAINING,
                $numberOfActions
            );
            $this->game->setGameStateValue(
                SALES_ORDER_FLIPS_REMAINING,
                $numberOfActions
            );
        }
    }
}
