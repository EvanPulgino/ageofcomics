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
        $ideaGenres = $args[1];

        $card = $this->game->cardManager->gainStaringComicCard(
            $activePlayerId,
            $comicGenre
        );
        $this->game->notifyAllPlayers(
            "gainStartingComic",
            clienttranslate('${player_name} gains a ${comic_genre} comic'),
            [
                "player_name" => $this->game->playerManager
                    ->getPlayer($activePlayerId)
                    ->getName(),
                "player_id" => $activePlayerId,
                "comic_genre" => $card->getGenre(),
                "comic_card" => $card->getUiData(0),
            ]
        );
        $this->game->notifyPlayer(
            $activePlayerId,
            "gainStartingComicPrivate",
            clienttranslate('You gain a ${comic_genre} comic'),
            [
                "player_name" => $this->game->playerManager
                    ->getPlayer($activePlayerId)
                    ->getName(),
                "player_id" => $activePlayerId,
                "comic_genre" => $card->getGenre(),
                "comic_card" => $card->getUiData($activePlayerId),
            ]
        );
    }
}
