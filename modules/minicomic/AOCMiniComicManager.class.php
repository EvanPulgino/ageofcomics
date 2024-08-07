<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 * *
 * Mini comic manager, handles all mini comic related logic
 *
 * @EvanPulgino
 */

class AOCMiniComicManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Setup mini comics for a new game
     *
     * @return void
     */
    public function setupNewGame() {
        $this->createMiniComics(CARD_TYPE_COMIC);
        $this->createMiniComics(CARD_TYPE_RIPOFF);
    }

    /**
     * Adjust the fans of a mini comic
     *
     * @param AOCComicCard|AOCRipoffCard $comic The comic to adjust
     * @param int $amount The amount to adjust by
     * @return int The new income level of the comic
     */
    public function adjustMiniComicFans($comic, $amount) {
        $miniComic = $this->getCorrespondingMiniComic($comic);
        $currentIncomeLevel = CHART_INCOME_LEVELS[$miniComic->getFans()];
        $miniComic->setFans($miniComic->getFans() + $amount);
        $newIncomeLevel = CHART_INCOME_LEVELS[$miniComic->getFans()];
        $this->saveMiniComic($miniComic);
        return $newIncomeLevel - $currentIncomeLevel;
    }

    /**
     * Get tht mini-comic matching a comic
     *
     * @param AOCMiniComic $miniComic The miniComic to adjust
     */
    public function adjustMiniComicFansFromSelf($miniComic, $amount) {
        $currentIncomeLevel = CHART_INCOME_LEVELS[$miniComic->getFans()];
        $miniComic->setFans($miniComic->getFans() + $amount);
        $newIncomeLevel = CHART_INCOME_LEVELS[$miniComic->getFans()];
        $this->saveMiniComic($miniComic);
        return $newIncomeLevel - $currentIncomeLevel;
    }

    /**
     * Get tht mini-comic matching a comic
     *
     * @param AOCComicCard|AOCRipoffCard $comic The comic to match
     */
    public function getCorrespondingMiniComic($comic) {
        $type = $comic->getTypeId();
        $typeArg =
            $type == CARD_TYPE_COMIC
                ? $comic->getBonus()
                : $comic->getRipoffKey();
        $genre = $comic->getGenreId();
        $sql = "SELECT mini_comic_id id, mini_comic_type type, mini_comic_type_arg typeArg, mini_comic_genre genre, mini_comic_location location, mini_comic_location_arg locationArg, mini_comic_owner playerId, mini_comic_fans fans FROM mini_comic WHERE mini_comic_type = {$type} AND mini_comic_type_arg = {$typeArg} AND mini_comic_genre = {$genre}";

        $row = self::getObjectFromDb($sql);
        return new AOCMiniComic($row);
    }

    public function getMiniComic($miniComicId) {
        $sql = "SELECT mini_comic_id id, mini_comic_type type, mini_comic_type_arg typeArg, mini_comic_genre genre, mini_comic_location location, mini_comic_location_arg locationArg, mini_comic_owner playerId, mini_comic_fans fans FROM mini_comic WHERE mini_comic_id = {$miniComicId}";
        $row = self::getObjectFromDb($sql);
        return new AOCMiniComic($row);
    }

    public function getMiniComicUiData($miniComicId) {
        $miniComic = $this->getMiniComic($miniComicId);
        return $miniComic->getUiData();
    }

    /**
     * Get all mini comics from database
     *
     * @return AOCMiniComic[] All mini comics
     */
    public function getMiniComics() {
        $sql =
            "SELECT mini_comic_id id, mini_comic_type type, mini_comic_type_arg typeArg, mini_comic_genre genre, mini_comic_location location, mini_comic_location_arg locationArg, mini_comic_owner playerId, mini_comic_fans fans FROM mini_comic";
        $rows = self::getCollectionFromDb($sql);

        $miniComics = [];
        foreach ($rows as $row) {
            $miniComics[] = new AOCMiniComic($row);
        }

        return $miniComics;
    }

    /**
     * Get all mini comics formatted for UI
     *
     * @return array All mini comics formatted for UI
     */
    public function getMiniComicsUiData() {
        $miniComics = $this->getMiniComics();
        $uiData = [];
        foreach ($miniComics as $miniComic) {
            $uiData[] = $miniComic->getUiData();
        }

        return $uiData;
    }

    /**
     * Get all mini comics from a player of a specific genre
     *
     * @param int $playerId The ID of the player
     * @param int $genreId The ID of the genre
     * @return AOCMiniComic[] All mini comics from a player of a specific genre
     */
    public function getMiniComicsByPlayerAndGenre($playerId, $genreId) {
        $sql = "SELECT mini_comic_id id, mini_comic_type type, mini_comic_type_arg typeArg, mini_comic_genre genre, mini_comic_location location, mini_comic_location_arg locationArg, mini_comic_owner playerId, mini_comic_fans fans FROM mini_comic WHERE mini_comic_owner = {$playerId} AND mini_comic_genre = {$genreId}";
        $rows = self::getCollectionFromDb($sql);

        $miniComics = [];
        foreach ($rows as $row) {
            $miniComics[] = new AOCMiniComic($row);
        }

        return $miniComics;
    }

    /**
     * Get all mini comics from a player
     *
     * @param int $playerId The ID of the player
     * @return AOCMiniComic[] All mini comics from a player
     */
    public function getMiniComicsByPlayer($playerId) {
        $sql = "SELECT mini_comic_id id, mini_comic_type type, mini_comic_type_arg typeArg, mini_comic_genre genre, mini_comic_location location, mini_comic_location_arg locationArg, mini_comic_owner playerId, mini_comic_fans fans FROM mini_comic WHERE mini_comic_owner = {$playerId}";
        $rows = self::getCollectionFromDb($sql);

        $miniComics = [];
        foreach ($rows as $row) {
            $miniComics[] = new AOCMiniComic($row);
        }

        return $miniComics;
    }

    /**
     * Get how much income a mini comic generates
     *
     * @param AOCMiniComic $miniComic The mini comic to get income for
     */
    public function getMiniComicIncomeLevel($miniComic) {
        if ($miniComic->getFans() > 10) {
            return CHART_INCOME_LEVELS[$miniComic->getFans() - 10] + 6;
        }
        return CHART_INCOME_LEVELS[$miniComic->getFans()];
    }

    /**
     * Get all mini comics owned by players
     *
     * @return AOCMiniComic[] All mini comics owned by players
     */
    public function getOwnedMiniComics() {
        $sql =
            "SELECT mini_comic_id id, mini_comic_type type, mini_comic_type_arg typeArg, mini_comic_genre genre, mini_comic_location location, mini_comic_location_arg locationArg, mini_comic_owner playerId, mini_comic_fans fans FROM mini_comic WHERE mini_comic_owner IS NOT NULL";
        $rows = self::getCollectionFromDb($sql);

        $miniComics = [];
        foreach ($rows as $row) {
            $miniComics[] = new AOCMiniComic($row);
        }

        return $miniComics;
    }

    /**
     * Save a mini comic to the database
     *
     * @param AOCMiniComic $miniComic The mini comic to save
     * @return void
     */
    public function saveMiniComic($miniComic) {
        $sql = "UPDATE mini_comic SET mini_comic_location = {$miniComic->getLocation()}, mini_comic_location_arg = {$miniComic->getLocationArg()}, mini_comic_owner = {$miniComic->getPlayerId()}, mini_comic_fans = {$miniComic->getFans()} WHERE mini_comic_id = {$miniComic->getId()}";

        self::DbQuery($sql);
    }

    /**
     * Create mini comics for a new game, by type
     *
     * @param int $comicType The type of mini comic to create
     * @return void
     */
    private function createMiniComics($comicType) {
        $comics = $comicType == CARD_TYPE_COMIC ? COMIC_CARDS : RIPOFF_CARDS;

        $sql =
            "INSERT INTO mini_comic (mini_comic_type, mini_comic_type_arg, mini_comic_genre, mini_comic_location, mini_comic_location_arg) VALUES ";
        $values = [];
        foreach ($comics as $genreId => $genre) {
            foreach ($genre as $comicKey => $comicName) {
                $values[] =
                    "({$comicType}, {$comicKey}, {$genreId}, " .
                    LOCATION_SUPPLY .
                    ", {$comicKey})";
            }
        }
        $sql .= implode(", ", $values);
        self::DbQuery($sql);
    }
}
