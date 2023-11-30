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
        // Get the active player
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Check if the player has enough money to develop a comic from the deck
        $canDevelopFromDeck = $activePlayer->getMoney() >= 4;

        // Get the text to display in the game state panel if the player can develop a comic from the deck
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
     * @param bool $topOfDeck Whether the player is developing the top card of the deck
     * @return void
     */
    public function developComic($comicId, $topOfDeck) {
        // Get the active player
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Develop (draw) card from market
        $comic = $this->game->cardManager->drawCard(
            $activePlayer->getId(),
            $comicId,
            CARD_TYPE_COMIC
        );

        $notificationItemString = $topOfDeck
            ? $this->game->formatNotificationString(
                $comic->getGenre(),
                $comic->getGenreId()
            )
            : $this->game->formatNotificationString(
                $comic->getName(),
                $comic->getGenreId()
            );
        
        $notificationString = $topOfDeck
            ? clienttranslate('${player_name} develops a ${itemString} from the top card of the deck')
            : clienttranslate('${player_name} develops ${itemString}');

        // Notify players of the card drawn. If not active player, only show the card back
        $this->game->notifyAllPlayers(
            "developComic",
            $notificationString,
            [
                "player" => $activePlayer->getUiData(),
                "player_id" => $activePlayer->getId(),
                "player_name" => $activePlayer->getName(),
                "comic" => $comic->getUiData(0),
                "itemString" => $notificationItemString,
            ]
        );
        // Notify active player of the card drawn. Show the face-up card
        $this->game->notifyPlayer(
            $activePlayer->getId(),
            "developComicPrivate",
            clienttranslate('${player_name} develops ${comic_name}'),
            [
                "player" => $activePlayer->getUiData(),
                "player_name" => $activePlayer->getName(),
                "comic" => $comic->getUiData($activePlayer->getId()),
                "comic_name" => $this->game->formatNotificationString(
                    $comic->getName(),
                    $comic->getGenreId()
                ),
            ]
        );

        // If the player has more than 6 cards in hand, transition to the discardCards state
        // Otherwise, transition to the nextPlayerTurn state
        if (
            $this->game->cardManager->getCountForHandSizeCheck(
                $activePlayer->getId()
            ) > 6
        ) {
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
        // Get the active player
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Player pays $4 to develop a comic from the deck
        $this->payForDeckDevelop($activePlayer, $genre);

        // Find and develop (draw) the next comic of the specified genre from the deck
        $comicToDevelop = $this->findNextComicOfGenre($activePlayer, $genre);
        $comic = $this->game->cardManager->drawCard(
            $activePlayer->getId(),
            $comicToDevelop->getId(),
            CARD_TYPE_COMIC
        );

        // Notify players of the card drawn. If not active player, only show the card back
        $this->game->notifyAllPlayers(
            "developComic",
            clienttranslate('${player_name} develops a ${genre} comic'),
            [
                "player" => $activePlayer->getUiData(),
                "player_name" => $activePlayer->getName(),
                "genre" => $this->game->formatNotificationString(
                    $comic->getGenre(),
                    $comic->getGenreId()
                ),
                "comic" => $comic->getUiData(0),
            ]
        );
        // Notify active player of the card drawn. Show the face-up card
        $this->game->notifyPlayer(
            $activePlayer->getId(),
            "developComicPrivate",
            clienttranslate('${player_name} develops ${comic_name}'),
            [
                "player" => $activePlayer->getUiData(),
                "player_id" => $activePlayer->getId(),
                "player_name" => $activePlayer->getName(),
                "comic" => $comic->getUiData($activePlayer->getId()),
                "comic_name" => $this->game->formatNotificationString(
                    $comic->getName(),
                    $comic->getGenreId()
                ),
            ]
        );

        // If the player has more than 6 cards in hand, transition to the discardCards state
        // Otherwise, transition to the nextPlayerTurn state
        if (
            $this->game->cardManager->getCountForHandSizeCheck(
                $activePlayer->getId()
            ) > 6
        ) {
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
        // Get the deck of comic cards
        $comicDeck = $this->game->cardManager->getCards(
            CARD_TYPE_COMIC,
            null,
            LOCATION_DECK,
            CARD_LOCATION_ARG_DESC
        );

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
                        '${player_name} discards ${comicName} from the deck'
                    ),
                    [
                        "player" => $activePlayer->getUiData(),
                        "player_name" => $activePlayer->getName(),
                        "card" => $comicCard->getUiData(0),
                        "comicName" => $this->game->formatNotificationString(
                            $comicCard->getName(),
                            $comicCard->getGenreId()
                        ),
                    ]
                );
            }

            // If we've discarded all cards from the deck, reshuffle the discard pile
            if ($comicToDevelop == null) {
                $this->game->cardManager->shuffleDiscardPile(CARD_TYPE_COMIC);
                $comicDeck = $this->game->cardManager->getCards(
                    CARD_TYPE_COMIC,
                    null,
                    LOCATION_DECK,
                    CARD_LOCATION_ARG_DESC
                );
                $this->game->notifyAllPlayers(
                    "reshuffleDiscardPile",
                    clienttranslate(
                        '${player_name} reshuffles the comic discard pile'
                    ),
                    [
                        "player" => $activePlayer->getUiData(),
                        "player_name" => $activePlayer->getName(),
                        "deck" => $this->game->cardManager->getCardsUiData(
                            $activePlayer->getId(),
                            CARD_TYPE_COMIC,
                            null,
                            LOCATION_DECK
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
     * @param AOCPlayer $player The active player
     * @param string $genre The genre of the comic card to develop
     * @return void
     */
    private function payForDeckDevelop($player, $genre) {
        $this->game->playerManager->adjustPlayerMoney($player, -4);
        $this->game->notifyAllPlayers(
            "adjustMoney",
            clienttranslate(
                '${player_name} pays $4 to develop a ${genre} comic'
            ),
            [
                "amount" => -4,
                "genre" => $this->game->formatNotificationString(
                    $genre,
                    GENRE_KEY_FROM_NAME[$genre]
                ),
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
            ]
        );
    }
}
