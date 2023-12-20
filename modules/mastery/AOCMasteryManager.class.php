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
 * Mastery token manager, handles all mastery token related logic
 *
 * @EvanPulgino
 */

class AOCMasteryManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Setup mastery tokens for a new game
     *
     * @return void
     */
    public function setupNewGame() {
        foreach (GENRE_KEYS as $genre) {
            $this->game->DbQuery(
                "INSERT INTO mastery_token (mastery_token_genre) VALUES ($genre)"
            );
        }
    }

    /**
     * Claim a mastery token
     *
     * @param int $playerId The player claiming the token
     * @param int $genre The genre of the mastery token
     * @param int $comicCount The number of comics the player has in the genre
     * @return void
     */
    public function claimMasteryToken($playerId, $genre, $comicCount) {
        $this->game->DbQuery(
            "UPDATE mastery_token SET mastery_token_owner = $playerId, mastery_token_comic_count = $comicCount WHERE mastery_token_genre = $genre"
        );
    }

    /**
     * Get a mastery token
     *
     * @param int $genre The genre of the mastery token
     * @return AOCMasteryToken The mastery token
     */
    public function getMasteryToken($genre) {
        $sql =
            "SELECT mastery_token_id id, mastery_token_genre genre, mastery_token_owner playerId, mastery_token_comic_count comicCount FROM mastery_token WHERE mastery_token_genre = $genre";
        $row = $this->game->getObjectFromDb($sql);
        return new AOCMasteryToken($row);
    }

    /**
     * Get all mastery tokens
     *
     * @return AOCMasteryToken[] Array of all mastery tokens
     */
    public function getMasteryTokens() {
        $sql =
            "SELECT mastery_token_id id, mastery_token_genre genre, mastery_token_owner playerId, mastery_token_comic_count comicCount FROM mastery_token";
        $rows = $this->game->getCollectionFromDb($sql);

        $tokens = [];
        foreach ($rows as $row) {
            $tokens[] = new AOCMasteryToken($row);
        }
        return $tokens;
    }

    /**
     * Get all mastery tokens in UI data format
     *
     * @return array An array of mastery tokens in UI data format
     */
    public function getMasteryTokensUiData() {
        $tokens = $this->getMasteryTokens();
        $uiData = [];
        foreach ($tokens as $token) {
            $uiData[] = $token->getUiData();
        }
        return $uiData;
    }

    /**
     * Check if a player has a mastery token
     *
     * @param int $playerId The player to check
     * @param int $genre The genre to check
     * @return bool True if the player has the mastery token in the genre
     */
    public function playerHasMasteryToken($playerId, $genre) {
        $sql = "SELECT mastery_token_id id, mastery_token_genre genre, mastery_token_owner playerId, mastery_token_comic_count comicCount FROM mastery_token WHERE mastery_token_owner = $playerId AND mastery_token_genre = $genre";
        $rows = $this->game->getCollectionFromDb($sql);
        return count($rows) > 0;
    }
}
