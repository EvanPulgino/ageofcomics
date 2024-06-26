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
 * Backend functions used by the enterIncreaseCreatives State
 *
 * This state handles backend actions for the enter increase creatives state.
 *
 * @EvanPulgino
 */

class AOCEnterIncreaseCreativesState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the EnterIncreaseCreatives state
     *
     * Args:
     * - None
     */
    public function getArgs($playerId = null) {
        return [];
    }

    public function stEnterIncreaseCreatives() {
        // Determine who has the ability to increase creatives
        // Players can increase creatives if:
        // - They have at least one printed comic
        // - And can affored to increase creatives on at least one comic

        $this->game->gamestate->setAllPlayersMultiactive();

        $eligiblePlayers = $this->getEligiblePlayers();

        if (
            !$this->game->gamestate->setPlayersMultiactive(
                $eligiblePlayers,
                "startActionsPhase",
                true
            )
        ) {
            $this->game->gamestate->initializePrivateStateForAllActivePlayers();
        }
    }

    private function getEligiblePlayers() {
        $players = $this->game->playerManager->getPlayers();
        $eligiblePlayers = [];

        foreach ($players as $key => $player) {
            $playerComics = $this->game->cardManager->getPrintedComicsByPlayer(
                $player->getId()
            );

            // If player has zero printed comics, they are not eligible
            if (count($playerComics) === 0) {
                continue;
            }

            $canIncreaseCreatives = false;

            foreach ($playerComics as $comic) {
                if ($this->canAffordToIncreaseCreatives($player, $comic)) {
                    $canIncreaseCreatives = true;
                    break;
                }
            }

            if ($canIncreaseCreatives) {
                $eligiblePlayers[] = $player->getId();
            }
        }

        return $eligiblePlayers;
    }

    /**
     * Determines if a player can afford to increase creatives on a comic
     *
     * @param AOCPlayer $player - The player to check
     * @param AOCComic $comic - The comic to check
     */
    private function canAffordToIncreaseCreatives($player, $comic) {
        $playerMoney = $player->getMoney();

        // Get the creatives for the comic
        $comicArtist = $this->game->cardManager->getArtistCardForPrintedComic(
            $player->getId(),
            $comic->getLocationArg()
        );
        $comicWriter = $this->game->cardManager->getWriterCardForPrintedComic(
            $player->getId(),
            $comic->getLocationArg()
        );

        // If neither creative has the same genre as the comic, the creative cannot be increased
        if (
            $comicArtist->getGenreId() !== $comic->getGenreId() &&
            $comicWriter->getGenreId() !== $comic->getGenreId()
        ) {
            return false;
        }

        $costToIncrease = 0;

        // If both creatives have the same genre as the comic, and have different values, the cost is $1
        if (
            $comicArtist->getGenreId() === $comic->getGenreId() &&
            $comicWriter->getGenreId() === $comic->getGenreId() &&
            $comicArtist->getValue() !== $comicWriter->getValue()
        ) {
            $costToIncrease = 1;
        } elseif (
            $comicArtist->getGenreId() === $comic->getGenreId() &&
            $comicWriter->getGenreId() === $comic->getGenreId() &&
            $comicArtist->getValue() === $comicWriter->getValue()
        ) {
            //If both creatives have the same genre as the comic, and have the same value, the cost is the value being increased to

            // If the value is 3, the creative cannot be increased
            if ($comicArtist->getValue() === 3) {
                return false;
            }

            $costToIncrease = $comicArtist->getValue() + 1;
        } else {
            // If we get here, one creative has the same genre as the comic and the other does not
            // Get the creative that has the same genre as the comic
            $sameGenreCreative =
                $comicArtist->getGenreId() === $comic->getGenreId()
                    ? $comicArtist
                    : $comicWriter;

            // If the creative is already at 3, the creative cannot be increased
            if ($sameGenreCreative->getValue() === 3) {
                return false;
            }

            // Otherwise the cost to increase is the value the matching creative will increase to (always one higher)
            $costToIncrease = $sameGenreCreative->getValue() + 1;
        }

        return $playerMoney >= $costToIncrease;
    }
}
