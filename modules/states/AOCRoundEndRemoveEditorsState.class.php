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
 * Backend functions used by the roundEndRemoveEditors State
 *
 * This state handles the remove editors fans step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndRemoveEditorsState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndRemoveEditors state
     *
     * Args:
     * - None
     */
    public function getArgs($playerId = null) {
        return [];
    }

    /**
     * Remove all editors from the board and return them to their owners
     *
     * @return void
     */
    public function stRoundEndRemoveEditors() {
        // Get all the players
        $players = $this->game->playerManager->getPlayers();

        // Iterate over each player
        foreach ($players as $player) {
            // Get all the editors owned by the player
            $editors = $this->game->editorManager->getPlayerEditorsOnBoard(
                $player->getId()
            );

            $hasExtraEditor = count($editors) === 5;

            // Iterate over each editor
            foreach ($editors as $editor) {
                if ($hasExtraEditor) {
                    // If the player has an extra editor, return it to the board
                    $this->game->editorManager->moveEditor(
                        $editor->getId(),
                        LOCATION_EXTRA_EDITOR
                    );
                    $hasExtraEditor = false;
                    $this->game->notifyAllPlayers(
                        "moveEditorToExtraEditorSpace",
                        "",
                        [
                            "editor" => $editor->getUiData(),
                        ]
                    );
                } else {
                    // Otherwise, return it to the player
                    $this->game->editorManager->moveEditor(
                        $editor->getId(),
                        LOCATION_PLAYER_AREA
                    );
                    $this->game->notifyAllPlayers(
                        "moveEditorToPlayerArea",
                        "",
                        [
                            "editor" => $editor->getUiData(),
                            "player" => $player->getUiData(),
                        ]
                    );
                }
            }
        }

        // Go to next state
        $this->game->gamestate->nextState("refillCards");
    }
}
