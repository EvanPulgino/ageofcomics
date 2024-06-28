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
    public function getArgs($playerId = null) {
        $activePlayer = $this->game->playerManager->getActivePlayer();
        $selectedActionSpace = $this->game->getGameStateValue(
            SELECTED_ACTION_SPACE
        );
        $hasWalked =
            $this->game->getGameStateValue(HAS_WALKED) == "1" ? true : false;
        $this->setNumberOfActions();
        $paidForCurrentSpace =
            $this->game->getGameStateValue(PAID_FOR_CURRENT_SPACE) == "1"
                ? true
                : false;

        return [
            "selectedActionSpace" => $selectedActionSpace,
            "hasWalked" => $hasWalked,
            "paidForCurrentSpace" => $paidForCurrentSpace,
            "remainingCollectActions" => $this->game->getGameStateValue(
                SALES_ORDER_COLLECTS_REMAINING
            ),
            "remainingFlipActions" => $this->game->getGameStateValue(
                SALES_ORDER_FLIPS_REMAINING
            ),
            "tickets" => intval($activePlayer->getTickets()),
            "salesAgentLocation" => intval($activePlayer->getAgentLocation()),
            "playerMoney" => intval($activePlayer->getMoney()),
            "playerCount" => sizeof($this->game->playerManager->getPlayers()),
        ];
    }

    public function collectSalesOrder($salesOrderId, $playerIdToPay) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Collect the sales order
        $this->game->salesOrderManager->collectSalesOrder(
            $salesOrderId,
            $activePlayer->getId()
        );

        $collectedSalesOrder = $this->game->salesOrderManager->getSalesOrder(
            $salesOrderId
        );

        // Decrement the number of sales order collects remaining
        $this->game->setGameStateValue(
            SALES_ORDER_COLLECTS_REMAINING,
            $this->game->getGameStateValue(SALES_ORDER_COLLECTS_REMAINING) - 1
        );

        // Pay the player who already on the space that arrived last
        $this->payLastArrivedPlayer($activePlayer, $playerIdToPay);

        // Notify all players that the sales order was collected
        $this->game->notifyAllPlayers(
            "salesOrderCollected",
            clienttranslate(
                '${player_name} collects a value ${value} ${genre} sales order'
            ),
            [
                "player" => $activePlayer->getUiData(),
                "player_name" => $activePlayer->getName(),
                "value" => $collectedSalesOrder->getValue(),
                "genre" => $collectedSalesOrder->getGenre(),
                "salesOrder" => $collectedSalesOrder->getUiData(),
            ]
        );

        // Check if the sales order can be fulfilled by any printed comics
        $printedComics = $this->game->cardManager->getPrintedComicsByPlayer(
            $activePlayer->getId()
        );
        $eligibleComics = [];
        foreach ($printedComics as $comic) {
            if (
                $this->canComicFulfillSalesOrder(
                    $activePlayer,
                    $comic,
                    $collectedSalesOrder
                )
            ) {
                $eligibleComics[] = $comic;
            }
        }

        if (count($eligibleComics) > 1) {
            // If there is more than one eligible comic, move to new state to select how to fulfill the sales order
        } else {
            // If there is only one eligible comic, fulfill the sales order automatically
            if (count($eligibleComics) === 1) {
                $comic = $eligibleComics[0];

                // Increase comic fans
                $incomeChange = $this->game->miniComicManager->adjustMiniComicFans(
                    $comic,
                    $collectedSalesOrder->getFans()
                );

                $miniComic = $this->game->miniComicManager->getCorrespondingMiniComic(
                    $comic
                );

                // Adjust player income
                $this->game->playerManager->adjustPlayerIncome(
                    $activePlayer,
                    $incomeChange
                );

                // Discard the sales order
                $this->game->salesOrderManager->discardSalesOrder(
                    $collectedSalesOrder->getId()
                );

                // Notify all players that the sales order was fulfilled
                $this->game->notifyAllPlayers(
                    "salesOrderFulfilled",
                    clienttranslate(
                        '${player_name} fulfills a value ${value} ${genre} sales order using ${comicName}, gaining ${fans} ${fanPlural}'
                    ),
                    [
                        "player" => $activePlayer->getUiData(),
                        "player_name" => $activePlayer->getName(),
                        "value" => $collectedSalesOrder->getValue(),
                        "genre" => $collectedSalesOrder->getGenre(),
                        "salesOrder" => $collectedSalesOrder->getUiData(),
                        "comicName" => $this->game->formatNotificationString(
                            $comic->getName(),
                            $comic->getGenreId()
                        ),
                        "fans" => $collectedSalesOrder->getFans(),
                        "fanPlural" =>
                            $collectedSalesOrder->getFans() == 1
                                ? "fan"
                                : "fans",
                        "incomeChange" => $incomeChange,
                        "miniComic" => $miniComic->getUiData(),
                    ]
                );
            }

            // Re-enter sales action state
            $this->game->gamestate->nextState("continueSales");
        }
    }

    public function endSales() {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Reset sales variables
        $this->game->setGameStateValue(HAS_WALKED, 0);
        $this->game->setGameStateValue(PAID_FOR_CURRENT_SPACE, 0);
        $this->game->setGameStateValue(SALES_ORDER_COLLECTS_REMAINING, -1);
        $this->game->setGameStateValue(SALES_ORDER_FLIPS_REMAINING, -1);

        // Notify all players that the active player has ended their sales
        $this->game->notifyAllPlayers(
            "salesEnded",
            clienttranslate('${player_name} ends their sales actoion'),
            [
                "player" => $activePlayer->getUiData(),
                "player_name" => $activePlayer->getName(),
            ]
        );

        // End the sales action state
        $this->game->gamestate->nextState("nextPlayerTurn");
    }

    public function flipSalesOrder($salesOrderId, $playerIdToPay) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Flip the sales order
        $this->game->salesOrderManager->flipSalesOrder($salesOrderId);

        $flippedSalesOrder = $this->game->salesOrderManager->getSalesOrder(
            $salesOrderId
        );

        // Decrement the number of sales order flips remaining
        $this->game->setGameStateValue(
            SALES_ORDER_FLIPS_REMAINING,
            $this->game->getGameStateValue(SALES_ORDER_FLIPS_REMAINING) - 1
        );

        // Pay the player who already on the space that arrived last
        $this->payLastArrivedPlayer($activePlayer, $playerIdToPay);

        // Notify all players that the sales order was flipped
        $this->game->notifyAllPlayers(
            "salesOrderFlipped",
            clienttranslate(
                '${player_name} flips and reveals a value ${value} ${genre} sales order'
            ),
            [
                "player" => $activePlayer->getUiData(),
                "player_name" => $activePlayer->getName(),
                "value" => $flippedSalesOrder->getValue(),
                "genre" => $flippedSalesOrder->getGenre(),
                "salesOrder" => $flippedSalesOrder->getUiData(),
            ]
        );

        // Re-enter sales action state
        $this->game->gamestate->nextState("continueSales");
    }

    public function moveSalesAgent($space) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Set new location of the sales agent
        $this->game->playerManager->movePlayerSalesAgent($activePlayer, $space);

        // Reset paid for current space
        $this->game->setGameStateValue(PAID_FOR_CURRENT_SPACE, 0);

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
                    "arrived" => $activePlayer->getAgentArrived(),
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
                    "arrived" => $activePlayer->getAgentArrived(),
                    "moneyAdjustment" => -2,
                ]
            );
        }

        // Re-enter sales action state
        $this->game->gamestate->nextState("continueSales");
    }

    public function moveSalesAgentWithTicket($space) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Set new location of the sales agent
        $this->game->playerManager->movePlayerSalesAgent($activePlayer, $space);

        // Reset paid for current space
        $this->game->setGameStateValue(PAID_FOR_CURRENT_SPACE, 0);

        // Spend a ticket
        $this->game->playerManager->adjustPlayerTickets($activePlayer, -1);

        $this->game->notifyAllPlayers(
            "playerUsedTicket",
            clienttranslate(
                '${player_name} uses a Super-transport ticket to move their sales agent'
            ),
            [
                "player" => $this->game->playerManager
                    ->getActivePlayer()
                    ->getUiData(),
                "player_name" => $this->game->playerManager
                    ->getActivePlayer()
                    ->getName(),
                "space" => $space,
                "arrived" => $activePlayer->getAgentArrived(),
            ]
        );

        // Re-enter sales action state
        $this->game->gamestate->nextState("continueSales");
    }

    private function canComicFulfillSalesOrder($player, $comic, $salesOrder) {
        if ($comic->getGenre() === $salesOrder->getGenre()) {
            // Get the creatives for the comic
            $comicArtist = $this->game->cardManager->getArtistCardForPrintedComic(
                $player->getId(),
                $comic->getLocationArg()
            );
            $comicWriter = $this->game->cardManager->getWriterCardForPrintedComic(
                $player->getId(),
                $comic->getLocationArg()
            );
            $comicValue =
                $comicArtist->getDisplayValue() +
                $comicWriter->getDisplayValue();

            if ($comicValue >= $salesOrder->getValue()) {
                return true;
            }
        }
        return false;
    }

    private function payLastArrivedPlayer($activePlayer, $playerIdToPay) {
        if ($playerIdToPay > 0) {
            if ($this->game->getGameStateValue(PAID_FOR_CURRENT_SPACE) == 0) {
                $playerToPay = $this->game->playerManager->getPlayer(
                    $playerIdToPay
                );
                $this->game->playerManager->adjustPlayerMoney(
                    $activePlayer,
                    -2
                );
                $this->game->playerManager->adjustPlayerMoney($playerToPay, 2);
                $this->game->setGameStateValue(PAID_FOR_CURRENT_SPACE, 1);

                $this->game->notifyAllPlayers(
                    "payPlayerForSpace",
                    clienttranslate(
                        '${player_name} pays 2 money to ${player_to_pay_name} to interact with the tiles on their space'
                    ),
                    [
                        "player" => $activePlayer->getUiData(),
                        "player_name" => $activePlayer->getName(),
                        "player_to_pay" => $playerToPay->getUiData(),
                        "player_to_pay_name" => $playerToPay->getName(),
                        "moneyAdjustment" => 2,
                    ]
                );
            }
        }
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
