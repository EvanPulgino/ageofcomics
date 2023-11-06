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
 * AOCPlayerActions.php
 *
 * All actions initiated by players
 *
 */

class AOCPlayerActions {
    private $game;
    public function __construct($game) {
        $this->game = $game;
    }

    function confirmDiscard($args) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);
        $cardsToDiscard = explode(",", $args[0]);

        foreach ($cardsToDiscard as $cardId) {
            $discardedCard = $this->game->cardManager->discardCard($cardId);
            $this->game->notifyAllPlayers(
                "discardCard",
                clienttranslate('${player_name} discards ${type_singular}'),
                [
                    "player" => $activePlayer->getUiData(),
                    "player_name" => $activePlayer->getName(),
                    "card" => $discardedCard->getUiData($activePlayerId),
                    "type_singular" =>
                        $discardedCard->getTypeId() == 1
                            ? "an artist"
                            : "a writer",
                ]
            );
        }

        $this->game->gamestate->nextState("nextPlayerTurn");
    }

    function confirmGainIdeas($args) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);
        $ideasFromBoard = explode(",", $args[0]);
        $ideasFromSupply = explode(",", $args[1]);

        if ($ideasFromBoard[0] == "") {
            $ideasFromBoard = [];
        }

        foreach ($ideasFromBoard as $ideaGenre) {
            $this->game->playerManager->gainIdeaFromBoard(
                $activePlayer,
                GENRES[$ideaGenre]
            );
        }

        foreach ($ideasFromSupply as $ideaGenre) {
            $this->game->playerManager->gainIdeaFromSupply(
                $activePlayer,
                GENRES[$ideaGenre]
            );
        }

        $this->game->gamestate->nextState("nextPlayerTurn");
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

        $this->game->playerManager->adjustPlayerMoney($activePlayer->getId(), -4);
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

        if (
            count($this->game->cardManager->getPlayerHand($activePlayerId)) > 6
        ) {
            $this->game->gamestate->nextState("discardCards");
        } else {
            $this->game->gamestate->nextState("nextPlayerTurn");
        }
    }

    function hireCreative($args) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);
        $cardId = $args[0];
        $creativeType = $args[1];
        $cardTypeId =
            $creativeType == "artist" ? CARD_TYPE_ARTIST : CARD_TYPE_WRITER;

        $card = $this->game->cardManager->drawCard(
            $activePlayerId,
            $cardId,
            $cardTypeId
        );

        if ($card->getIdeas() == 1) {
            $this->game->playerManager->gainIdeaFromHiringCreative(
                $activePlayer,
                $card
            );
        }

        $this->game->notifyAllPlayers(
            "hireCreative",
            clienttranslate(
                '${player_name} hires a value ${cardValue} ${creative}'
            ),
            [
                "player" => $activePlayer->getUiData(),
                "player_id" => $activePlayerId,
                "player_name" => $activePlayer->getName(),
                "card" => $card->getUiData(0),
                "cardValue" => $card->getValue(),
                "creative" => ucfirst($creativeType),
            ]
        );
        $this->game->notifyPlayer(
            $activePlayerId,
            "hireCreativePrivate",
            clienttranslate('You hire a value ${cardValue} ${creative}'),
            [
                "player" => $activePlayer->getUiData(),
                "player_id" => $activePlayerId,
                "player_name" => $activePlayer->getName(),
                "card" => $card->getUiData($activePlayerId),
                "cardValue" => $card->getValue(),
                "creative" => ucfirst($creativeType),
            ]
        );

        if ($creativeType == "artist") {
            $this->game->setGameStateValue(CAN_HIRE_ARTIST, 0);
        }

        if ($creativeType == "writer") {
            $this->game->setGameStateValue(CAN_HIRE_WRITER, 0);
        }

        if (
            $this->game->getGameStateValue(CAN_HIRE_ARTIST) == 0 &&
            $this->game->getGameStateValue(CAN_HIRE_WRITER) == 0
        ) {
            if (
                count(
                    $this->game->cardManager->getPlayerHand($activePlayerId)
                ) > 6
            ) {
                $this->game->gamestate->nextState("discardCards");
            } else {
                $this->game->gamestate->nextState("nextPlayerTurn");
            }
        } else {
            $this->game->gamestate->nextState("performNextHire");
        }
    }

    function selectActionSpace($args) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);
        $space = $args[0];
        $actionKey = floor($space / 10000);
        $actionName = ACTION_STRING_FROM_KEY[$actionKey];

        $editor = $this->game->editorManager->movePlayerEditorToActionSpace(
            $activePlayerId,
            $space
        );
        $this->game->notifyAllPlayers(
            "placeEditor",
            clienttranslate(
                '${player_name} places an editor on the ${actionName} action'
            ),
            [
                "player" => $activePlayer->getUiData(),
                "player_name" => $activePlayer->getName(),
                "editor" => $editor->getUiData(),
                "space" => $space,
                "actionName" => ucfirst($actionName),
            ]
        );

        $this->game->setGameStateValue(SELECTED_ACTION_SPACE, $space);

        switch ($actionKey) {
            case HIRE_ACTION:
                $this->game->setGameStateValue(CAN_HIRE_ARTIST, 1);
                $this->game->setGameStateValue(CAN_HIRE_WRITER, 1);
                $this->game->gamestate->nextState("performHire");
                break;
            case DEVELOP_ACTION:
                $this->game->gamestate->nextState("performDevelop");
                break;
            case IDEAS_ACTION:
                $this->game->gamestate->nextState("performIdeas");
                break;
            case PRINT_ACTION:
                $this->game->gamestate->nextState("performPrint");
                break;
            case ROYALTIES_ACTION:
                $this->game->playerManager->gainRoyalties(
                    $activePlayer,
                    $space
                );
                $this->game->gamestate->nextState("nextPlayerTurn");
                break;
            case SALES_ACTION:
                $this->game->gamestate->nextState("performSales");
                break;
        }
    }

    function selectStartItems($args) {
        $activePlayerId = $this->game->getActivePlayerId();
        $comicGenre = $args[0];
        $ideaGenres = explode(",", $args[1]);

        $this->game->cardManager->gainStaringComicCard(
            $activePlayerId,
            $comicGenre
        );

        foreach ($ideaGenres as $ideaGenre) {
            $this->game->playerManager->gainStartingIdea(
                $activePlayerId,
                GENRES[$ideaGenre]
            );
        }

        $this->game->gamestate->nextState("nextPlayerSetup");
    }
}
