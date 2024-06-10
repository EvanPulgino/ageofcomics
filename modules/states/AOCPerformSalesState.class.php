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

    /**
     * If this is the first time the state is entered, set the number of sales order flip and collect actions
     */
    public function stInitSales() {
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
