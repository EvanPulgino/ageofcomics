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

    function developComic($args) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);
        $comicId = $args[0];

        $comic = $this->game->cardManager->developComic(
            $activePlayerId,
            $comicId
        );

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

        if (
            count($this->game->cardManager->getPlayerHand($activePlayerId)) > 6
        ) {
            $this->game->gamestate->nextState("discardCards");
        } else {
            $this->game->gamestate->nextState("nextPlayerTurn");
        }
    }

    function developFromGenre($args) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);
        $genre = $args[0];

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

        $comicDeck = $this->game->cardManager->getComicDeckDesc();

        $cardToDevelop = null;

        while ($cardToDevelop == null) {
            foreach ($comicDeck as $comicCard) {
                if ($comicCard->getGenre() == $genre) {
                    $cardToDevelop = $comicCard;
                    break;
                }

                $this->game->cardManager->discardCard($comicCard->getId());
                $this->game->notifyAllPlayers(
                    "discardCardFromDeck",
                    clienttranslate(
                        '${player_name} discards a ${genre} comic from the deck'
                    ),
                    [
                        "player" => $activePlayer->getUiData(),
                        "player_name" => $activePlayer->getName(),
                        "card" => $comicCard->getUiData($activePlayerId),
                        "genre" => $comicCard->getGenre(),
                    ]
                );
            }

            if ($cardToDevelop == null) {
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

        $comic = $this->game->cardManager->developComic(
            $activePlayerId,
            $cardToDevelop->getId()
        );

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

        $queryParams = [
            "card_owner" => $this->game->getActivePlayerId(),
            "NOT card_location" => LOCATION_PLAYER_MAT,
        ];
        $cardsInHand = $this->game->cardManager->findCards($queryParams);

        if (count($cardsInHand) > 6) {
            $this->game->gamestate->nextState("discardCards");
        } else {
            $this->game->gamestate->nextState("nextPlayerTurn");
        }
    }
}
