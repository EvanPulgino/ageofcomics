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
 * Backend functions used by the roundEndRefillCards State
 *
 * This state handles the refill cards fans step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndRefillCardsState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndRefillCards state
     *
     * Args:
     * - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * Remove all cards in the market and refill it
     *
     * @return void
     */
    public function stRoundEndRefillCards() {
        // Get all the cards in the supply
        $cards = $this->game->cardManager->getCards(
            null,
            null,
            LOCATION_SUPPLY
        );

        // Iterate over each card and discard it
        foreach ($cards as $card) {
            $this->game->cardManager->discardCard($card->getId());
            $this->game->notifyAllPlayers("discardCardFromSupply", "", [
                "card" => $card->getUiData(0),
            ]);
        }

        // Refill the supply
        $this->dealCardsToSupplyByCardType(CARD_TYPE_ARTIST);
        $this->dealCardsToSupplyByCardType(CARD_TYPE_WRITER);
        $this->dealCardsToSupplyByCardType(CARD_TYPE_COMIC);

        // Go to next round
        $this->game->gamestate->nextState("startNewRound");
    }

    /**
     * Deals cards to the supply.
     * The number of cards to deal varies by player count and is stored in the CARD_SUPPLY_SIZE game state variable.
     *
     * @param $cardType The type of card to deal
     */
    private function dealCardsToSupplyByCardType($cardType) {
        $deck = $this->game->cardManager->getCards(
            $cardType,
            null,
            LOCATION_DECK,
            null,
            CARD_LOCATION_ARG_DESC
        );

        $numberOfCardsToDraw = $this->game->getGameStateValue(CARD_SUPPLY_SIZE);

        while ($numberOfCardsToDraw > 0) {
            if (count($deck) === 0) {
                // If the deck is empty, shuffle the discard pile and add it to the deck
                $this->game->cardManager->shuffleDiscardPile($cardType);
                $deck = $this->game->cardManager->getCards(
                    $cardType,
                    null,
                    LOCATION_DECK,
                    null,
                    CARD_LOCATION_ARG_DESC
                );
                $this->game->notifyAllPlayers("reshuffleDiscardPile", "", [
                    "deck" => $this->game->cardManager->getCardsUiData(
                        0,
                        $cardType,
                        null,
                        LOCATION_DECK
                    ),
                ]);
            }

            $drawnCard = array_pop($deck);
            $drawnCard->setLocation(LOCATION_SUPPLY);
            $drawnCard->setLocationArg(0);
            $this->game->cardManager->saveCard($drawnCard);
            $numberOfCardsToDraw--;

            $this->game->notifyAllPlayers("dealCardToSupply", "", [
                "card" => $drawnCard->getUiData(0),
            ]);
        }
    }
}
