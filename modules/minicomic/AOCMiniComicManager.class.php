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
 * AOCMiniComicManager.class.php
 *
 * Mini comic manager
 *
 */

class AOCMiniComicManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    public function setupNewGame() {
        $this->createMiniComics(CARD_TYPE_COMIC);
        $this->createMiniComics(CARD_TYPE_RIPOFF);
    }

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

    public function getMiniComicsUiData() {
        $miniComics = $this->getMiniComics();
        $uiData = [];
        foreach ($miniComics as $miniComic) {
            $uiData[] = $miniComic->getUiData();
        }

        return $uiData;
    }

    private function createMiniComics($comicType) {
        $comics = $comicType == CARD_TYPE_COMIC ? COMIC_CARDS : RIPOFF_CARDS;

        $sql = "INSERT INTO mini_comic (mini_comic_type, mini_comic_type_arg, mini_comic_genre, mini_comic_location, mini_comic_location_arg) VALUES ";
        $values = [];
        foreach ($comics as $genreId => $genre) {
            foreach ($genre as $comicKey => $comicName) {
                $values[] = "({$comicType}, {$comicKey}, {$genreId}, " . LOCATION_SUPPLY . ", {$comicKey})";
            }
        }
        $sql .= implode(", ", $values);
        self::DbQuery($sql);
    }

    private function saveMiniComic($miniComic) {
        $sql = "UPDATE mini_comic SET mini_comic_location = {$miniComic->getLocation()}, mini_comic_location_arg = {$miniComic->getLocationArg()}, mini_comic_owner = {$miniComic->getPlayerId()}, mini_comic_fans = {$miniComic->getFans()} WHERE mini_comic_id = {$miniComic->getId()}";
    }

    private function saveMiniComics($miniComics) {
        foreach ($miniComics as $miniComic) {
            $this->saveMiniComic($miniComic);
        }
    }
}
