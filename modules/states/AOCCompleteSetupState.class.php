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
 * @EvanPulgino
 */

class AOCCompleteSetupState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    function getArgs() { return []; }

    public function stCompleteSetup() {
        $this->game->cardManager->shuffleStartingDeck(CARD_TYPE_ARTIST);
        $this->game->cardManager->shuffleStartingDeck(CARD_TYPE_WRITER);
        $this->game->cardManager->shuffleStartingDeck(CARD_TYPE_COMIC);

        $artistCards = $this->game->cardManager->dealCardsToSupply(
            CARD_TYPE_ARTIST
        );
        $writerCards = $this->game->cardManager->dealCardsToSupply(
            CARD_TYPE_WRITER
        );
        $comicCards = $this->game->cardManager->dealCardsToSupply(
            CARD_TYPE_COMIC
        );

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

}