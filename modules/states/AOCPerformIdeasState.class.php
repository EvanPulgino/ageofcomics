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
 * Backend functions used by the performIdeas State
 *
 * @EvanPulgino
 */

class AOCPerformIdeasState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the PerformIdeas state
     *
     * Args:
     * - selectedActionSpace => The id of the action space where the player placed their editor
     * - ideasFromBoard => The number of ideas the player can take from the board
     *
     * @return array The list of args used by the PerformIdeas state
     */
    function getArgs() {
        $selectedActionSpace = $this->game->getGameStateValue(
            SELECTED_ACTION_SPACE
        );
        $ideasFromBoard = 0;

        switch ($selectedActionSpace) {
            case 30001:
                $ideasFromBoard = 2;
                break;
            case 30002:
                $ideasFromBoard = 1;
                break;
            case 30003:
                $ideasFromBoard = 1;
                break;
            default:
                break;
        }

        return [
            "selectedActionSpace" => $selectedActionSpace,
            "ideasFromBoard" => $ideasFromBoard,
        ];
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
}
