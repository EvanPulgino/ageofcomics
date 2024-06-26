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
 * Backend functions used by the roundEndSubtractFans State
 *
 * This state handles the subtract fans step of the end of round phase.
 *
 * @EvanPulgino
 */

class AOCRoundEndSubtractFansState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the RoundEndSubtractFans state
     *
     * Args:
     * - None
     */
    public function getArgs($playerId = null) {
        return [];
    }

    /**
     * Subtract one fan (to a minimum of one) from each printed comic
     *
     * @return void
     */
    public function stRoundEndSubtractFans() {
        // Get all printed comics
        $printedComics = $this->game->miniComicManager->getOwnedMiniComics();

        // Subtract one fan from each printed comic
        foreach ($printedComics as $printedComic) {
            $this->subtractFanFromMiniComic($printedComic);
        }

        $this->game->gamestate->nextState("removeEditors");
    }

    private function subtractFanFromMiniComic($miniComic) {
        if ($miniComic->getFans() > 1) {
            $incomeChange = $this->game->miniComicManager->adjustMiniComicFansFromSelf(
                $miniComic,
                -1
            );
            $player = $this->game->playerManager->getPlayer(
                $miniComic->getPlayerId()
            );
            $this->game->playerManager->adjustPlayerIncome(
                $player,
                $incomeChange
            );
            $this->game->notifyAllPlayers(
                "adjustMiniComic",
                clienttranslate('${comicName} loses a fan'),
                [
                    "player" => $player->getUiData(),
                    "player_name" => $player->getName(),
                    "comicName" => $this->game->formatNotificationString(
                        $miniComic->getName(),
                        $miniComic->getGenreId()
                    ),
                    "miniComic" => $this->game->miniComicManager->getMiniComicUiData(
                        $miniComic->getId()
                    ),
                    "incomeChange" => $incomeChange,
                ]
            );
        }
    }
}
