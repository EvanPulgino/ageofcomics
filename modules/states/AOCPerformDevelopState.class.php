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
 * Backend functions used by the performDevelop State
 *
 * In this state, the player can develop (draw) a comic card.
 * They can take a face-up card from the market or top of the deck.
 * Or they can pay $4 to develop the next card of a specified genre from the deck.
 *
 * @EvanPulgino
 */

class AOCPerformDevelopState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the PerformDevelop state
     *
     * Args:
     * - availableGenres => The number of combined comics in the deck and discard for each genre
     * - canDevelopFromDeck => Whether the player has enough money to develop a comic from the deck
     * - fromDeckText => The text to display in the game state panel if the player can develop a comic from the deck
     *
     * @return array The list of args used by the PerformDevelop state
     */
    public function getArgs() {
        $activePlayer = $this->game->playerManager->getActivePlayer();
        $canDevelopFromDeck = $activePlayer->getMoney() >= 4;
        $fromDeckText = $canDevelopFromDeck
            ? clienttranslate(
                "or pay \$4 to develop the next comic of a genre from the deck"
            )
            : "";

        return [
            "availableGenres" => [
                "crime" => $this->game->cardManager->getAvailableComicCount(
                    GENRE_CRIME
                ),
                "horror" => $this->game->cardManager->getAvailableComicCount(
                    GENRE_HORROR
                ),
                "romance" => $this->game->cardManager->getAvailableComicCount(
                    GENRE_ROMANCE
                ),
                "scifi" => $this->game->cardManager->getAvailableComicCount(
                    GENRE_SCIFI
                ),
                "superhero" => $this->game->cardManager->getAvailableComicCount(
                    GENRE_SUPERHERO
                ),
                "western" => $this->game->cardManager->getAvailableComicCount(
                    GENRE_WESTERN
                ),
            ],
            "canDevelopFromDeck" => $canDevelopFromDeck,
            "fromDeckText" => $fromDeckText,
        ];
    }

    /**
     * A player develops a comic card
     *
     * @param int $comicId The id of the comic card to develop
     * @return void
     */
    public function developComic($comicId) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);

        // Develop (draw) card from market
        $comic = $this->game->cardManager->drawCard(
            $activePlayerId,
            $comicId,
            CARD_TYPE_COMIC
        );

        // Notify players of the card drawn. If not active player, only show the card back
        $this->game->notifyAllPlayers(
            "developComic",
            clienttranslate('${player_name} develops a ${genre} comic'),
            [
                "player" => $activePlayer->getUiData(),
                "player_id" => $activePlayerId,
                "player_name" => $activePlayer->getName(),
                "genre" => $comic->getGenre(),
                "comic" => $comic->getUiData(0),
            ]
        );
        // Notify active player of the card drawn. Show the face-up card
        $this->game->notifyPlayer(
            $activePlayerId,
            "developComicPrivate",
            clienttranslate('${player_name} develops a ${genre} comic'),
            [
                "player" => $activePlayer->getUiData(),
                "player_id" => $activePlayerId,
                "player_name" => $activePlayer->getName(),
                "genre" => $comic->getGenre(),
                "comic" => $comic->getUiData($activePlayerId),
            ]
        );

        // If the player has more than 6 cards in hand, transition to the discardCards state
        // Otherwise, transition to the nextPlayerTurn state
        if ($this->game->cardManager->getCountForHandSizeCheck($activePlayerId) > 6) {
            $this->game->gamestate->nextState("discardCards");
        } else {
            $this->game->gamestate->nextState("nextPlayerTurn");
        }
    }

    /**
     * A player develops a comic card of a specified genre from the deck
     *
     * @param string $genre The genre of the comic card to develop
     * @return void
     */
    public function developFromGenre($genre) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);

        // Player pays $4 to develop a comic from the deck
        $this->payForDeckDevelop($activePlayer);

        // Find and develop (draw) the next comic of the specified genre from the deck
        $comicToDevelop = $this->findNextComicOfGenre($activePlayer, $genre);
        $comic = $this->game->cardManager->drawCard(
            $activePlayerId,
            $comicToDevelop->getId(),
            CARD_TYPE_COMIC
        );

        // Notify players of the card drawn. If not active player, only show the card back
        $this->game->notifyAllPlayers(
            "developComic",
            clienttranslate('${player_name} develops a ${genre} comic'),
            [
                "player" => $activePlayer->getUiData(),
                "player_id" => $activePlayerId,
                "player_name" => $activePlayer->getName(),
                "genre" => $comic->getGenre(),
                "comic" => $comic->getUiData(0),
            ]
        );
        // Notify active player of the card drawn. Show the face-up card
        $this->game->notifyPlayer(
            $activePlayerId,
            "developComicPrivate",
            clienttranslate('${player_name} develops a ${genre} comic'),
            [
                "player" => $activePlayer->getUiData(),
                "player_id" => $activePlayerId,
                "player_name" => $activePlayer->getName(),
                "genre" => $comic->getGenre(),
                "comic" => $comic->getUiData($activePlayerId),
            ]
        );

        // If the player has more than 6 cards in hand, transition to the discardCards state
        // Otherwise, transition to the nextPlayerTurn state
        if ($this->game->cardManager->getCountForHandSizeCheck($activePlayerId) > 6) {
            $this->game->gamestate->nextState("discardCards");
        } else {
            $this->game->gamestate->nextState("nextPlayerTurn");
        }
    }

    /**
     * Finds the next comic card of a specified genre from the deck
     *
     * @param AOCPlayer $activePlayer The active player
     * @param string $genre The genre of the comic card to find
     * @return AOCComicCard The next comic card of the specified genre from the deck
     */
    private function findNextComicOfGenre($activePlayer, $genre) {
        $comicDeck = $this->game->cardManager->getComicDeckDesc();

        $comicToDevelop = null;

        // While we haven't found a comic of the specified genre, discard cards from the deck
        while ($comicToDevelop == null) {
            foreach ($comicDeck as $comicCard) {
                // If we find a comic of the specified genre, return it
                if ($comicCard->getGenre() == $genre) {
                    $comicToDevelop = $comicCard;
                    break;
                }

                // Otherwise, discard the card from the deck and notify players
                $this->game->cardManager->discardCard($comicCard->getId());
                $this->game->notifyAllPlayers(
                    "discardCardFromDeck",
                    clienttranslate(
                        '${player_name} discards a ${genre} comic from the deck'
                    ),
                    [
                        "player" => $activePlayer->getUiData(),
                        "player_name" => $activePlayer->getName(),
                        "card" => $comicCard->getUiData(0),
                        "genre" => $comicCard->getGenre(),
                    ]
                );
            }

            // If we've discarded all cards from the deck, reshuffle the discard pile
            if ($comicToDevelop == null) {
                $this->game->cardManager->shuffleDiscardPile(CARD_TYPE_COMIC);
                $comicDeck = $this->game->cardManager->getComicDeckDesc();
                $this->game->notifyAllPlayers(
                    "reshuffleDiscardPile",
                    clienttranslate(
                        '${player_name} reshuffles the comic discard pile'
                    ),
                    [
                        "player" => $activePlayer->getUiData(),
                        "player_name" => $activePlayer->getName(),
                        "deck" => $this->game->cardManager->getDeckUiData(
                            CARD_TYPE_COMIC
                        ),
                    ]
                );
            }
        }
        return $comicToDevelop;
    }

    /**
     * Active player spends $4 to develop a comic card from the deck
     *
     * @param AOCPlayer $activePlayer The active player
     * @return void
     */
    private function payForDeckDevelop($activePlayer) {
        $this->game->playerManager->adjustPlayerMoney(
            $activePlayer->getId(),
            -4
        );
        $this->game->notifyAllPlayers(
            "adjustMoney",
            clienttranslate('${player_name} pays $4 to develop a comic'),
            [
                "player" => $activePlayer->getUiData(),
                "player_name" => $activePlayer->getName(),
                "amount" => -4,
            ]
        );
    }
}
