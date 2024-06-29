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
 * Backend functions used by the performSalesFulfillOrder State
 *
 * This state handles backend actions used during the perform sales fulfill order state.
 *
 * @EvanPulgino
 */

class AOCPerformSalesFulfillOrderState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the PerformSalesFulfillOrder state
     *
     * Args:
     * - None
     */
    public function getArgs($playerId = null) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        $salesOrderBeingFulfilled = $this->game->getGameStateValue(
            SALES_ORDER_BEING_FULFILLED
        );
        $salesOrder = $this->game->salesOrderManager->getSalesOrder(
            $salesOrderBeingFulfilled
        );
        $cardsOnPlayerMat = $this->game->cardManager->getCardsUiData(
            $playerId,
            null,
            null,
            LOCATION_PLAYER_MAT,
            $activePlayer->getId()
        );
        return [
            "salesOrderBeingFulfilled" => $salesOrder->getUiData(),
            "cardsOnPlayerMat" => $cardsOnPlayerMat,
        ];
    }

    public function selectComicForOrder($comicId, $salesOrderId) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        $comicCard = $this->game->cardManager->getCard($comicId);
        $salesOrder = $this->game->salesOrderManager->getSalesOrder(
            $salesOrderId
        );

        // Increase comic fans
        $incomeChange = $this->game->miniComicManager->adjustMiniComicFans(
            $comicCard,
            $salesOrder->getFans()
        );

        // Get the mini comic corresponding to the comic card
        // Get the mini comic corresponding to the improved comic
        $miniComic = $this->game->miniComicManager->getCorrespondingMiniComic(
            $comicCard
        );

        // Adjust player income
        $this->game->playerManager->adjustPlayerIncome(
            $activePlayer,
            $incomeChange
        );

        // Discard the sales order
        $this->game->salesOrderManager->discardSalesOrder($salesOrder->getId());

        $miniComic->setCssClass();

        // Notify all players that the sales order was fulfilled
        $this->game->notifyAllPlayers(
            "salesOrderFulfilled",
            clienttranslate(
                '${player_name} fulfills a value ${value} ${genre} sales order using ${comicName}, gaining ${fans} ${fanPlural}'
            ),
            [
                "player" => $activePlayer->getUiData(),
                "player_name" => $activePlayer->getName(),
                "value" => $salesOrder->getValue(),
                "genre" => $salesOrder->getGenre(),
                "salesOrder" => $salesOrder->getUiData(),
                "comicName" => $this->game->formatNotificationString(
                    $comicCard->getName(),
                    $comicCard->getGenreId()
                ),
                "fans" => $salesOrder->getFans(),
                "fanPlural" => $salesOrder->getFans() == 1 ? "fan" : "fans",
                "incomeChange" => $incomeChange,
                "miniComic" => $miniComic->getUiData(),
            ]
        );

        // Clear the sales order being fulfilled
        $this->game->setGameStateValue(SALES_ORDER_BEING_FULFILLED, 0);

        // Move to continue sales state
        $this->game->gamestate->nextState("continueSales");
    }
}
