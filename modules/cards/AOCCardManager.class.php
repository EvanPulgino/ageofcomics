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
 * AOCCardManager.class.php
 *
 * AOC card manager
 *
 */

class AOCCardManager extends APP_GameClass {
    private $game;
    private $genres = [
        GENRE_CRIME,
        GENRE_HORROR,
        GENRE_ROMANCE,
        GENRE_SCIFI,
        GENRE_SUPERHERO,
        GENRE_WESTERN,
    ];

    public function __construct($game) {
        $this->game = $game;
    }

    public function setupNewGame($players) {
        // Create cards
        $this->createComicCards();
        $this->createCreativeCards(CARD_TYPE_ARTIST);
        $this->createCreativeCards(CARD_TYPE_WRITER);
        $this->createRipoffCards();

        // Deal starting cards
        $this->dealStartingCreative($players);

        // Shuffle decks
        $this->shuffleStartingDeck(CARD_TYPE_ARTIST);
        $this->shuffleStartingDeck(CARD_TYPE_WRITER);
    }

    public function gainStaringComicCard($playerId, $genreId) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card WHERE card_genre = " .
            $genreId .
            " AND card_type = 3 AND card_owner IS NULL ";
        $rows = self::getObjectListFromDB($sql);

        shuffle($rows);
        $card = new AOCComicCard($rows[0]);

        $card->setPlayerId($playerId);
        $card->setLocation(LOCATION_HAND);
        $card->setLocationArg($card->getTypeId() * 100 + $card->getGenreId());

        $this->saveCard($card);

        $this->game->notifyAllPlayers(
            "gainStartingComic",
            clienttranslate('${player_name} gains a ${comic_genre} comic'),
            [
                "player_name" => $this->game->playerManager
                    ->getPlayer($playerId)
                    ->getName(),
                "player_id" => $playerId,
                "comic_genre" => $card->getGenre(),
                "comic_card" => $card->getUiData(0),
            ]
        );
        $this->game->notifyPlayer(
            $playerId,
            "gainStartingComicPrivate",
            clienttranslate('You gain a ${comic_genre} comic'),
            [
                "player_name" => $this->game->playerManager
                    ->getPlayer($playerId)
                    ->getName(),
                "player_id" => $playerId,
                "comic_genre" => $card->getGenre(),
                "comic_card" => $card->getUiData($playerId),
            ]
        );
    }

    public function getArtistCards() {
        $rows = $this->getCardsByType(CARD_TYPE_ARTIST);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCArtistCard($row);
        }
        return $cards;
    }

    public function getArtistDeck() {
        $rows = $this->getDeckByType(CARD_TYPE_ARTIST);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCArtistCard($row);
        }
        return $cards;
    }

    public function getComicCards() {
        $rows = $this->getCardsByType(CARD_TYPE_COMIC);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCComicCard($row);
        }
        return $cards;
    }

    public function getComicDeck() {
        $rows = $this->getDeckByType(CARD_TYPE_COMIC);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCComicCard($row);
        }
        return $cards;
    }

    public function getRipoffCards() {
        $rows = $this->getCardsByType(CARD_TYPE_RIPOFF);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCRipoffCard($row);
        }
        return $cards;
    }
    public function getWriterCards() {
        $rows = $this->getCardsByType(CARD_TYPE_WRITER);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCWriterCard($row);
        }
        return $cards;
    }

    public function getWriterDeck() {
        $rows = $this->getDeckByType(CARD_TYPE_WRITER);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCWriterCard($row);
        }
        return $cards;
    }

    public function getPlayerHand($playerId) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card WHERE card_owner = " .
            $playerId .
            " AND card_location = " .
            LOCATION_HAND .
            " ORDER BY card_location_arg";
        $rows = self::getObjectListFromDB($sql);

        $cards = [];
        foreach ($rows as $row) {
            switch ($row["type"]) {
                case CARD_TYPE_ARTIST:
                    $cards[] = new AOCArtistCard($row);
                    break;
                case CARD_TYPE_COMIC:
                    $cards[] = new AOCComicCard($row);
                    break;
                case CARD_TYPE_RIPOFF:
                    $cards[] = new AOCRipoffCard($row);
                    break;
                case CARD_TYPE_WRITER:
                    $cards[] = new AOCWriterCard($row);
                    break;
            }
        }
        return $cards;
    }

    public function getCardsUiData($cardType, $currentPlayerId) {
        $cards = [];
        switch ($cardType) {
            case CARD_TYPE_ARTIST:
                $cards = $this->getArtistCards();
                break;
            case CARD_TYPE_COMIC:
                $cards = $this->getComicCards();
                break;
            case CARD_TYPE_RIPOFF:
                $cards = $this->getRipoffCards();
                break;
            case CARD_TYPE_WRITER:
                $cards = $this->getWriterCards();
                break;
        }
        $uiData = [];
        foreach ($cards as $card) {
            $uiData[] = $card->getUiData($currentPlayerId);
        }
        return $uiData;
    }

    public function getDeckUiData($cardType, $currentPlayerId) {
        $cards = [];
        switch ($cardType) {
            case CARD_TYPE_ARTIST:
                $cards = $this->getArtistDeck();
                break;
            case CARD_TYPE_COMIC:
                $cards = $this->getComicDeck();
                break;
            case CARD_TYPE_WRITER:
                $cards = $this->getWriterDeck();
                break;
        }
        $uiData = [];
        foreach ($cards as $card) {
            $uiData[] = $card->getUiData($currentPlayerId);
        }
        return $uiData;
    }

    public function getHandUiData($playerId, $currentPlayerId) {
        $cards = $this->getPlayerHand($playerId);
        $uiData = [];
        foreach ($cards as $card) {
            $uiData[] = $card->getUiData($currentPlayerId);
        }
        return $uiData;
    }

    public function getPlayerHandsUiData($players, $currentPlayerId) {
        $uiData = [];
        foreach ($players as $player) {
            $uiData[$player->getId()] = $this->getHandUiData(
                $player->getId(),
                $currentPlayerId
            );
        }
        return $uiData;
    }

    private function createCreativeCards($creativeType) {
        $sql =
            "INSERT INTO card (card_type, card_type_arg, card_genre, card_location) VALUES ";
        $values = [];

        $typeName = $creativeType == CARD_TYPE_ARTIST ? ARTIST : WRITER;

        foreach ($this->genres as $genreKey) {
            $values[] = "({$creativeType}, 10, {$genreKey}, 0)";
            $values[] = "({$creativeType}, 21, {$genreKey}, 0)";
            $values[] = "({$creativeType}, 22, {$genreKey}, 0)";
            $values[] = "({$creativeType}, 30, {$genreKey}, 0)";
        }

        $sql .= implode(", ", $values);
        self::DbQuery($sql);
    }

    private function createComicCards() {
        $sql =
            "INSERT INTO card (card_type, card_type_arg, card_genre, card_location) VALUES ";
        $values = [];
        foreach (COMIC_CARDS as $genreKey => $comics) {
            foreach ($comics as $bonusKey => $bonus) {
                $values[] =
                    "(" . CARD_TYPE_COMIC . ", {$bonusKey}, {$genreKey}, 0)";
            }
        }

        $sql .= implode(", ", $values);
        self::DbQuery($sql);
    }

    private function createRipoffCards() {
        $sql =
            "INSERT INTO card (card_type, card_type_arg, card_genre, card_location) VALUES ";
        $values = [];
        foreach (RIPOFF_CARDS as $genreKey => $ripoffs) {
            foreach ($ripoffs as $ripoffKey => $ripoff) {
                $values[] =
                    "(" . CARD_TYPE_RIPOFF . ", {$ripoffKey}, {$genreKey}, 0)";
            }
        }

        $sql .= implode(", ", $values);
        self::DbQuery($sql);
    }

    private function dealStartingCreative($players) {
        // Get all level 2 cards
        $artistCards = $this->getArtistCards();
        $writerCards = $this->getWriterCards();

        $level2Artists = array_filter($artistCards, function ($card) {
            return $card->getValue() == 2;
        });
        $level2Writers = array_filter($writerCards, function ($card) {
            return $card->getValue() == 2;
        });

        // Shuffle
        shuffle($level2Artists);
        shuffle($level2Writers);

        foreach ($players as $player) {
            // Deal 1 card of each type to each player
            $artistCard = array_pop($level2Artists);
            $writerCard = array_pop($level2Writers);

            // Make sure cards are different genres
            while ($artistCard->getGenreId() == $writerCard->getGenreId()) {
                $level2Artists[] = $artistCard;
                $level2Writers[] = $writerCard;
                shuffle($level2Artists);
                shuffle($level2Writers);
                $artistCard = array_pop($level2Artists);
                $writerCard = array_pop($level2Writers);
            }

            $artistCard->setPlayerId($player->getId());
            $artistCard->setLocation(LOCATION_HAND);
            $artistCard->setLocationArg(
                $artistCard->getTypeId() * 100 +
                    $artistCard->getGenreId() +
                    $artistCard->getValue()
            );
            $this->saveCard($artistCard);

            $writerCard->setPlayerId($player->getId());
            $writerCard->setLocation(LOCATION_HAND);
            $writerCard->setLocationArg(
                $writerCard->getTypeId() * 100 +
                    $writerCard->getGenreId() +
                    $writerCard->getValue()
            );
            $this->saveCard($writerCard);
        }
    }

    private function getCardsByType($cardType) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card WHERE card_type = " .
            $cardType;
        $rows = self::getObjectListFromDB($sql);

        return $rows;
    }

    private function getDeckByType($cardType) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card WHERE card_type = " .
            $cardType .
            " AND card_location = " .
            LOCATION_DECK .
            " ORDER BY card_location_arg";
        $rows = self::getObjectListFromDB($sql);

        return $rows;
    }

    private function saveCard($card) {
        $sql = "UPDATE card SET card_location = {$card->getLocation()}, card_location_arg = {$card->getLocationArg()}, card_owner = {$card->getPlayerId()} WHERE card_id = {$card->getId()}";
        self::DbQuery($sql);
    }

    private function saveCards($cards) {
        foreach ($cards as $card) {
            $this->saveCard($card);
        }
    }

    private function shuffleStartingDeck($cardType) {
        $rows = $this->getCardsByType($cardType);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCCard($row);
        }

        $cardsInDeck = array_filter($cards, function ($card) {
            return $card->getLocation() == LOCATION_DECK;
        });

        shuffle($cardsInDeck);

        $locationArg = 0;
        foreach ($cardsInDeck as $card) {
            $card->setLocationArg($locationArg);
            $locationArg++;
        }

        $this->saveCards($cardsInDeck);
    }
}
