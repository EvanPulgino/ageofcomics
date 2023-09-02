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
    }
}
