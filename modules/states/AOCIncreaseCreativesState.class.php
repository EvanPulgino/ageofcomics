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
 * Backend functions used by the increaseCreatives State
 *
 * This state handles backend actions for the increase creatives state.
 *
 * @EvanPulgino
 */

class AOCIncreaseCreativesState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the IncreaseCreatives state
     *
     * Args:
     * - None
     */
    public function getArgs($playerId = null) {
        $currentPlayer = $this->game->playerManager->getPlayer($playerId);
        $cardsOnPlayerMat = $this->game->cardManager->getCardsUiData(
            $playerId,
            null,
            null,
            LOCATION_PLAYER_MAT,
            $playerId
        );

        return [
            "cardsOnPlayerMat" => $cardsOnPlayerMat,
            "currentPlayer" => $currentPlayer->getUiData(),
        ];
    }

    public function doubleTrain($playerId, $comicId, $artistId, $writerId) {
        $player = $this->game->playerManager->getPlayer($playerId);

        $this->game->cardManager->flagComicAsImproved($comicId);

        // Get the cards being improved, calculate the cost to train, and improve them
        $artist = $this->game->cardManager->getCard($artistId);
        $writer = $this->game->cardManager->getCard($writerId);
        $costToTrain = ($artist->getDisplayValue() + 1) * 2;
        $this->game->cardManager->improveCreativeCard($artist);
        $this->game->cardManager->improveCreativeCard($writer);

        // Player spends the cost to train
        $this->game->playerManager->adjustPlayerMoney(
            $player,
            $costToTrain * -1
        );

        $comicCard = $this->game->cardManager->getCard($comicId);

        // Notify players
        $this->game->notifyAllPlayers(
            "improveCreativeDouble",
            clienttranslate(
                '${player_name} pays ${paid} to use a train action on both the artist and the writer on ${comicName}, increasing each of their values to ${newValue}.'
            ),
            [
                "player_name" => $player->getName(),
                "player" => $player->getUiData(),
                "artistCard" => $artist->getUiData(0),
                "writerCard" => $writer->getUiData(0),
                "comicName" => $this->game->formatNotificationString(
                    $comicCard->getName(),
                    $comicCard->getGenreId()
                ),
                "newValue" => $artist->getDisplayValue(),
                "paid" => $costToTrain,
            ]
        );

        $this->completeIncreaseAction($player, $comicCard);
    }

    public function endIncreaseCreatives($playerId) {
        $this->game->cardManager->unflagComicsAsImproved($playerId);

        $this->game->gamestate->setPlayerNonMultiactive(
            $playerId,
            "startActionsPhase"
        );
    }

    public function learn($playerId, $comicId, $cardId) {
        $player = $this->game->playerManager->getPlayer($playerId);

        // Get the card being improved and improve it
        $card = $this->game->cardManager->getCard($cardId);
        $this->game->cardManager->improveCreativeCard($card);

        // Flag the comic as improved so it can't be improved again this round
        $this->game->cardManager->flagComicAsImproved($comicId);

        // Player spends $1 to learn
        $this->game->playerManager->adjustPlayerMoney($player, -1);

        $comicCard = $this->game->cardManager->getCard($comicId);

        // Notify players
        $this->game->notifyAllPlayers(
            "improveCreative",
            clienttranslate(
                '${player_name} pays $1 to use a learn action on the ${creativeType} on ${comicName}, increasing their value to ${newValue}.'
            ),
            [
                "player_name" => $player->getName(),
                "player" => $player->getUiData(),
                "card" => $card->getUiData(0),
                "creativeType" => $card->getType(),
                "comicName" => $this->game->formatNotificationString(
                    $comicCard->getName(),
                    $comicCard->getGenreId()
                ),
                "newValue" => $card->getDisplayValue(),
                "paid" => 1,
            ]
        );

        $this->completeIncreaseAction($player, $comicCard);
    }

    public function train($playerId, $comicId, $cardId) {
        $player = $this->game->playerManager->getPlayer($playerId);

        $this->game->cardManager->flagComicAsImproved($comicId);

        // Get the card being improved, calculate the cost to train, and improve it
        $card = $this->game->cardManager->getCard($cardId);
        $costToTrain = $card->getDisplayValue() + 1;
        $this->game->cardManager->improveCreativeCard($card);

        // Player spends the cost to train
        $this->game->playerManager->adjustPlayerMoney(
            $player,
            $costToTrain * -1
        );

        $comicCard = $this->game->cardManager->getCard($comicId);

        // Notify players
        $this->game->notifyAllPlayers(
            "improveCreative",
            clienttranslate(
                '${player_name} pays ${paid} to use a train action on the ${creativeType} on ${comicName}, increasing their value to ${newValue}.'
            ),
            [
                "player_name" => $player->getName(),
                "player" => $player->getUiData(),
                "card" => $card->getUiData(0),
                "creativeType" => $card->getType(),
                "comicName" => $this->game->formatNotificationString(
                    $comicCard->getName(),
                    $comicCard->getGenreId()
                ),
                "newValue" => $card->getDisplayValue(),
                "paid" => $costToTrain,
            ]
        );

        $this->completeIncreaseAction($player, $comicCard);
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

    private function completeIncreaseAction($player, $improvedComic) {
        // Get the sales orders for the player
        $salesOrders = $this->game->salesOrderManager->getSalesOrdersByPlayer(
            $player->getId()
        );

        // Check if any sales orders can be fulfilled
        foreach ($salesOrders as $salesOrder) {
            if (
                $this->canComicFulfillSalesOrder(
                    $player,
                    $improvedComic,
                    $salesOrder
                )
            ) {
                // Increase comic fans
                $incomeChange = $this->game->miniComicManager->adjustMiniComicFans(
                    $improvedComic,
                    $salesOrder->getFans()
                );

                $miniComic = $this->game->miniComicManager->getCorrespondingMiniComic(
                    $improvedComic
                );

                // Adjust player income
                $this->game->playerManager->adjustPlayerIncome(
                    $player,
                    $incomeChange
                );

                // Discard the sales order
                $this->game->salesOrderManager->discardSalesOrder(
                    $salesOrder->getId()
                );

                // Notify all players that the sales order was fulfilled
                $this->game->notifyAllPlayers(
                    "salesOrderFulfilled",
                    clienttranslate(
                        '${player_name} fulfills a value ${value} ${genre} sales order using ${comicName}, gaining ${fans} ${fanPlural}'
                    ),
                    [
                        "player" => $player->getUiData(),
                        "player_name" => $player->getName(),
                        "value" => $salesOrder->getValue(),
                        "genre" => $salesOrder->getGenre(),
                        "salesOrder" => $salesOrder->getUiData(),
                        "comicName" => $this->game->formatNotificationString(
                            $improvedComic->getName(),
                            $improvedComic->getGenreId()
                        ),
                        "fans" => $salesOrder->getFans(),
                        "fanPlural" =>
                            $salesOrder->getFans() == 1 ? "fan" : "fans",
                        "incomeChange" => $incomeChange,
                        "miniComic" => $miniComic->getUiData(),
                    ]
                );
            }
        }

        if ($this->playerCanContinueIncreasing($player)) {
            // If player can continue increasing, set them as active
            $this->game->gamestate->nextPrivateState(
                $player->getId(),
                "continue"
            );
        } else {
            // If player cannot continue increasing, unflag all comics and set them as non-active
            $this->endIncreaseCreatives($player->getId());
        }
    }

    private function playerCanContinueIncreasing($player) {
        $playerComics = $this->game->cardManager->getPrintedComicsByPlayer(
            $player->getId()
        );

        $canIncreaseCreatives = false;

        foreach ($playerComics as $comic) {
            if ($this->canAffordToIncreaseCreatives($player, $comic)) {
                $canIncreaseCreatives = true;
                break;
            }
        }

        return $canIncreaseCreatives;
    }

    /**
     * Determines if a player can afford to increase creatives on a comic
     *
     * @param AOCPlayer $player - The player to check
     * @param AOCComic $comic - The comic to check
     */
    private function canAffordToIncreaseCreatives($player, $comic) {
        if ($comic->getDisplayValue() === 1) {
            return false;
        }

        $playerMoney = $player->getMoney();

        // Get the creatives for the comic
        $comicArtist = $this->game->cardManager->getArtistCardForPrintedComic(
            $player->getId(),
            $comic->getLocationArg()
        );
        $comicWriter = $this->game->cardManager->getWriterCardForPrintedComic(
            $player->getId(),
            $comic->getLocationArg()
        );

        // If neither creative has the same genre as the comic, the creative cannot be increased
        if (
            $comicArtist->getGenreId() !== $comic->getGenreId() &&
            $comicWriter->getGenreId() !== $comic->getGenreId()
        ) {
            return false;
        }

        $costToIncrease = 0;

        // If both creatives have the same genre as the comic, and have different values, the cost is $1
        if (
            $comicArtist->getGenreId() === $comic->getGenreId() &&
            $comicWriter->getGenreId() === $comic->getGenreId() &&
            $comicArtist->getValue() !== $comicWriter->getValue()
        ) {
            $costToIncrease = 1;
        } elseif (
            $comicArtist->getGenreId() === $comic->getGenreId() &&
            $comicWriter->getGenreId() === $comic->getGenreId() &&
            $comicArtist->getValue() === $comicWriter->getValue()
        ) {
            //If both creatives have the same genre as the comic, and have the same value, the cost is the value being increased to

            // If the value is 3, the creative cannot be increased
            if ($comicArtist->getValue() === 3) {
                return false;
            }

            $costToIncrease = $comicArtist->getValue() + 1;
        } else {
            // If we get here, one creative has the same genre as the comic and the other does not
            // Get the creative that has the same genre as the comic
            $sameGenreCreative =
                $comicArtist->getGenreId() === $comic->getGenreId()
                    ? $comicArtist
                    : $comicWriter;

            // If the creative is already at 3, the creative cannot be increased
            if ($sameGenreCreative->getValue() === 3) {
                return false;
            }

            // Otherwise the cost to increase is the value the matching creative will increase to (always one higher)
            $costToIncrease = $sameGenreCreative->getValue() + 1;
        }

        return $playerMoney >= $costToIncrease;
    }
}
