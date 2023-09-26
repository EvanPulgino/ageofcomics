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
