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
 * During this state, players can take ideas from the board and supply.
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
    public function getArgs() {
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

    /**
     * The player gains all of the ideas they selected
     *
     * @param int[] $ideasFromBoard The ideas the player took from the board (using the genre's key)
     * @param int[] $ideasFromSupply The ideas the player took from the supply (using the genre's key)
     * @return void
     */
    public function confirmGainIdeas($ideasFromBoard, $ideasFromSupply) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // If the player did not take any ideas from the board, set the array to an empty array
        if ($ideasFromBoard[0] == "") {
            $ideasFromBoard = [];
        }

        // For each idea the player took from the board, gain that idea
        foreach ($ideasFromBoard as $ideaGenre) {
            $this->gainIdeaFromBoard($activePlayer, $ideaGenre);
        }

        // For each idea the player took from the supply, gain that idea
        foreach ($ideasFromSupply as $ideaGenre) {
            $this->gainIdeaFromSupply($activePlayer, $ideaGenre);
        }

        // Set the state to the next player's turn
        $this->game->gamestate->nextState("nextPlayerTurn");
    }

    /**
     * A player gains an idea from the board
     *
     * @param AOCPlayer $player The player gaining the idea
     * @param int $genre The genre key of the idea to gain
     * @return void
     */
    private function gainIdeaFromBoard($player, $genre) {
        // Adjust the player's ideas
        $this->game->playerManager->adjustPlayerIdeas($player, 1, $genre);

        // Remove the idea from the board
        $this->game->setGameStateValue("ideas_space_" . GENRES[$genre], 0);

        // Notify all players of the gain
        $this->game->notifyAllPlayers(
            "gainIdeaFromBoard",
            clienttranslate(
                '${player_name} gains a ${genreString} idea from the board'
            ),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "genre" => GENRES[$genre],
                "genreString" => $this->game->formatNotificationString(
                    GENRES[$genre],
                    $genre
                ),
            ]
        );
    }

    /**
     * A player gains an idea from the supply
     *
     * @param AOCPlayer $player The player gaining the idea
     * @param int $genre key The genre of the idea to gain
     * @return void
     */
    private function gainIdeaFromSupply($player, $genre) {
        // Adjust the player's ideas
        $this->game->playerManager->adjustPlayerIdeas($player, 1, $genre);

        // Notify all players of the gain
        $this->game->notifyAllPlayers(
            "gainIdeaFromSupply",
            clienttranslate(
                '${player_name} gains a ${genreString} idea from the supply'
            ),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "genre" => GENRES[$genre],
                "genreString" => $this->game->formatNotificationString(
                    GENRES[$genre],
                    $genre
                ),
            ]
        );
    }
}
