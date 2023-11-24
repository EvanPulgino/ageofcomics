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
 * Backend functions used by the completeSetup State
 *
 * This state is entered after all players have selected their starting components. Finishes game setup by creating Creative and Comic decks.
 *
 * @EvanPulgino
 */

class AOCCompleteSetupState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the CompleteSetup state
     *
     * Args:
     *  - None
     */
    public function getArgs() {
        return [];
    }

    /**
     * Shuffles the starting decks, deals cards to the supply, and moves to the next state
     */
    public function stCompleteSetup() {
        $artistCards = $this->shuffleAndDealCardsByType(CARD_TYPE_ARTIST);
        $writerCards = $this->shuffleAndDealCardsByType(CARD_TYPE_WRITER);
        $comicCards = $this->shuffleAndDealCardsByType(CARD_TYPE_COMIC);

        $this->game->notifyAllPlayers(
            "completeSetup",
            clienttranslate("Setup complete"),
            [
                "artistCards" => $artistCards,
                "writerCards" => $writerCards,
                "comicCards" => $comicCards,
            ]
        );

        $this->game->gamestate->nextState("startGame");
    }

    /**
     * Shuffles the starting deck of a given card type and deals cards to the supply.
     *
     * @param $cardType The type of card to shuffle and deal
     * @return array The updated deck and supply UiData of the given card type
     */
    private function shuffleAndDealCardsByType($cardType) {
        $this->shuffleStartingDeckByType($cardType);
        $this->dealStartingCardsToSupplyByCardType($cardType);

        return [
            "deck" => $this->game->cardManager->getCardsUiData(
                0,
                $cardType,
                null,
                LOCATION_DECK
            ),
            "supply" => $this->game->cardManager->getCardsUiData(
                0,
                $cardType,
                null,
                LOCATION_SUPPLY
            ),
        ];
    }

    /**
     * Shuffles the starting deck of a given card type.
     * Since this is the start of the game all remaining cards of the given type should be in the "void".
     *
     * @param $cardType The type of card to shuffle
     */
    private function shuffleStartingDeckByType($cardType) {
        $cards = $this->game->cardManager->getCards(
            $cardType,
            null,
            LOCATION_VOID,
            null
        );

        shuffle($cards);

        $locationArg = 0;
        foreach ($cards as $card) {
            $card->setLocation(LOCATION_DECK);
            $card->setLocationArg($locationArg);
            $this->game->cardManager->saveCard($card);
            $locationArg++;
        }
    }

    /**
     * Deals the starting cards to the supply.
     * The number of cards to deal varies by player count and is stored in the CARD_SUPPLY_SIZE game state variable.
     *
     * @param $cardType The type of card to deal
     */
    private function dealStartingCardsToSupplyByCardType($cardType) {
        $deck = $this->game->cardManager->getCards(
            $cardType,
            null,
            LOCATION_DECK,
            CARD_LOCATION_ARG_DESC
        );

        $numberOfCardsToDraw = $this->game->getGameStateValue(CARD_SUPPLY_SIZE);
        $startIndex = count($deck) - $numberOfCardsToDraw;
        $drawnCards = array_splice($deck, $startIndex, $numberOfCardsToDraw);

        foreach ($drawnCards as $card) {
            $card->setLocation(LOCATION_SUPPLY);
            $card->setLocationArg(0);
            $this->game->cardManager->saveCard($card);
        }
    }
}
