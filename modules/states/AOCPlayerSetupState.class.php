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
 * In this state, players select their starting comic and ideas
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
    public function getArgs() {
        return [
            "startIdeas" => $this->game->getGameStateValue(START_IDEAS),
        ];
    }

    /**
     * The player gains the starting comic and ideas they selected
     *
     * @param int $comicGenre The genre key of the starting comic
     * @param int[] $ideaGenres The genre keys of the starting ideas
     * @return void
     */
    public function selectStartItems($comicGenre, $ideaGenres) {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        // Gain the starting comic
        $this->gainStartingComic($activePlayer, $comicGenre);

        // Gain the starting ideas
        $this->gainStartingIdeas($activePlayer, $ideaGenres);

        // Move to the next player
        $this->game->gamestate->nextState("nextPlayerSetup");
    }

    /**
     * The player gains the starting comic they selected
     *
     * @param AOCPlayer $player The player gaining the starting comic
     * @param int $comicGenre The genre key  of the starting comic
     * @return void
     */
    private function gainStartingComic($player, $comicGenre) {
        // Find the comic of the chosen genre in the void
        $genreComicsInVoid = $this->game->cardManager->getCards(
            CARD_TYPE_COMIC,
            $comicGenre,
            LOCATION_VOID
        );

        // Shuffle the array and pop the last element
        shuffle($genreComicsInVoid);
        $comic = array_pop($genreComicsInVoid);

        // Draw the comic into the player's hand
        $drawnCard = $this->game->cardManager->drawCard(
            $player->getId(),
            $comic->getId(),
            CARD_TYPE_COMIC
        );

        // Notify all players of the comic gained (with facedown card), and notify the active player privately (so they can see the card they gained)
        $this->game->notifyAllPlayers(
            "gainStartingComic",
            clienttranslate('${player_name} gains a ${comic_genre} comic'),
            [
                "player_name" => $player->getName(),
                "player_id" => $player->getId(),
                "comic_genre" => $drawnCard->getGenre(),
                "comic_card" => $drawnCard->getUiData(0),
            ]
        );
        $this->game->notifyPlayer(
            $player->getId(),
            "gainStartingComicPrivate",
            clienttranslate('${player_name} gain ${comic_name}'),
            [
                "player_name" => $player->getName(),
                "player_id" => $player->getId(),
                "comic_name" => $drawnCard->getName(),
                "comic_card" => $drawnCard->getUiData($player->getId()),
            ]
        );
    }

    /**
     * The player gains the starting ideas they selected
     *
     * @param AOCPlayer $player The player gaining the starting ideas
     * @param int[] $ideaGenres The genre keys of the starting ideas
     * @return void
     */
    private function gainStartingIdeas($player, $ideaGenres) {
        // For each idea genre, gain an idea of that genre
        foreach ($ideaGenres as $ideaGenre) {
            $this->game->playerManager->adjustPlayerIdeas(
                $player->getId(),
                1,
                GENRES[$ideaGenre]
            );

            // Notify all players of the idea gained
            $this->game->notifyAllPlayers(
                "gainStartingIdea",
                clienttranslate('${player_name} gains a ${genre} idea'),
                [
                    "player_name" => $player->getName(),
                    "player_id" => $player->getId(),
                    "genre" => GENRES[$ideaGenre],
                ]
            );
        }
    }
}
