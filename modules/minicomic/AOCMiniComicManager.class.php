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

    /**
     * Save a mini comic to the database
     *
     * @param AOCMiniComic $miniComic The mini comic to save
     * @return void
     */
    private function saveMiniComic($miniComic) {
        $sql = "UPDATE mini_comic SET mini_comic_location = {$miniComic->getLocation()}, mini_comic_location_arg = {$miniComic->getLocationArg()}, mini_comic_owner = {$miniComic->getPlayerId()}, mini_comic_fans = {$miniComic->getFans()} WHERE mini_comic_id = {$miniComic->getId()}";
    }

    /**
     * Save all mini comics to the database
     *
     * @param AOCMiniComic[] $miniComics The mini comics to save
     * @return void
     */
    private function saveMiniComics($miniComics) {
        foreach ($miniComics as $miniComic) {
            $this->saveMiniComic($miniComic);
        }
    }
}
