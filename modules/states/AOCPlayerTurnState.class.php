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
 * Backend functions used by the playerTurn State
 *
 * @EvanPulgino
 */

class AOCPlayerTurnState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the playerTurn state
     *
     * Args:
     * - hireActionSpace => The id of the next available hire action space
     * - developActionSpace => The id of the next available develop action space
     * - ideasActionSpace => The id of the next available ideas action space
     * - printActionSpace => The id of the next available print action space
     * - royaltiesActionSpace => The id of the next available royalties action space
     * - salesActionSpace => The id of the next available sales action space
     */
    function getArgs() {
        return [
            "hireActionSpace" => $this->game->editorManager->getNextActionSpaceForEditor(
                LOCATION_ACTION_HIRE
            ),
            "developActionSpace" => $this->game->editorManager->getNextActionSpaceForEditor(
                LOCATION_ACTION_DEVELOP
            ),
            "ideasActionSpace" => $this->game->editorManager->getNextActionSpaceForEditor(
                LOCATION_ACTION_IDEAS
            ),
            "printActionSpace" => $this->game->editorManager->getNextActionSpaceForEditor(
                LOCATION_ACTION_PRINT
            ),
            "royaltiesActionSpace" => $this->game->editorManager->getNextActionSpaceForEditor(
                LOCATION_ACTION_ROYALTIES
            ),
            "salesActionSpace" => $this->game->editorManager->getNextActionSpaceForEditor(
                LOCATION_ACTION_SALES
            ),
        ];
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
}
