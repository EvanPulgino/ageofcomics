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
 * Backend functions used by the playerSetup State
 *
 * @EvanPulgino
 */

class AOCPlayerSetupState {
    private $game;
    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the playerSetup state
     *
     * Args:
     * - startIdeas => The number of starting ideas the player gets
     *
     * @return array The list of args used by the playerSetup state
     */
    function getArgs() {
        return [
            "startIdeas" => $this->game->getGameStateValue(START_IDEAS),
        ];
    }

    function selectStartItems($args) {
        $activePlayerId = $this->game->getActivePlayerId();

        $comicGenre = $args[0];
        $ideaGenres = $args[1];

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
