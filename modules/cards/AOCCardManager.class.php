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
 * Card manager class, handles all card related logic
 *
 * @EvanPulgino
 */

class AOCCardManager extends APP_GameClass {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Setup cards for a new game
     *
     * @param AOCPlayer[] $players An array of players
     * @return void
     */
    public function setupNewGame($players) {
        // Create cards
        $this->createComicCards();
        $this->createCreativeCards(CARD_TYPE_ARTIST);
        $this->createCreativeCards(CARD_TYPE_WRITER);
        $this->createRipoffCards();

        // Deal starting cards
        $this->dealStartingCreative($players);
    }

    /**
     * Send a specific card to its discard pile
     *
     * @param int $cardId The ID of the card being discarded
     * @return AOCCard The card that was discarded
     */
    public function discardCard($cardId) {
        $card = $this->getCard($cardId);
        $card->setLocation(LOCATION_DISCARD);
        $card->setLocationArg(0);
        $card->setPlayerId(0);
        $this->saveCard($card);

        return $card;
    }

    /**
     * Draw a specific card into a player's hand:
     * - Get the card from the database
     * - Set the player ID, location, and locationArg
     * - Save the card
     *
     * @param int $playerId The ID of the player drawing the card
     * @param int $cardId The ID of the card being drawn
     * @param int $cardType The type of card being drawn
     * @return AOCCard The card that was drawn
     */
    public function drawCard($playerId, $cardId, $cardType) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card WHERE card_id = " .
            $cardId;
        $row = self::getObjectFromDB($sql);
        $locationModifier = 0;
        switch ($cardType) {
            case CARD_TYPE_ARTIST:
                $card = new AOCArtistCard($row);
                $locationModifier = $card->getValue();
                break;
            case CARD_TYPE_COMIC:
                $card = new AOCComicCard($row);
                break;
            case CARD_TYPE_WRITER:
                $card = new AOCWriterCard($row);
                $locationModifier = $card->getValue();
                break;
        }

        $card->setPlayerId($playerId);
        $card->setLocation(LOCATION_HAND);
        $card->setLocationArg(
            $card->getTypeId() * 100 + $card->getGenreId() + $locationModifier
        );

        $this->saveCard($card);

        return $card;
    }

    /**
     * A player gains a starting comic card of a specific genre:
     * - Get all comic cards of the specified genre that are in the void
     * - Shuffle the cards
     * - Get the first card
     * - Set the player ID, location, and locationArg
     * - Save the card
     * - Notify all players that the player gained a starting comic card
     *
     * @param int $playerId The ID of the player gaining the card
     * @param int $genreId The ID of the genre of the card being gained
     * @return void
     */
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
            clienttranslate('${player_name} gain a ${comic_genre} comic'),
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

    /**
     * Gets a count of the number of cards a player has in their hand or hype area.
     * Used for checking if a player is over the hand size limit.
     *
     * @param int $playerId The ID of the player
     * @return int The number of cards the player has in their hand or hype area
     */
    public function getCountForHandSizeCheck($playerId) {
        $sql =
            "SELECT COUNT(*) FROM card WHERE card_owner = " .
            $playerId .
            " AND card_location = " .
            LOCATION_HAND .
            " OR card_location = " .
            LOCATION_HYPE;

        return self::getUniqueValueFromDB($sql);
    }

    /**
     * Search for all cards based on passed in query parameters
     *
     * @param array $queryParams An array of query parameters
     * @return AOCCard[] All cards matching the query parameters
     */
    public function findCards($queryParams) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card";
        $where = [];
        foreach ($queryParams as $key => $value) {
            $where[] = $key . " = " . $value;
        }
        if (count($where) > 0) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
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

    public function findCardsOrderedBy($queryParams, $orderBy) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card";
        $where = [];
        foreach ($queryParams as $key => $value) {
            $where[] = $key . " = " . $value;
        }
        if (count($where) > 0) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY " . $orderBy;
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

    /**
     * Get a card object from the database
     *
     * @param int $cardId The ID of the card
     * @return AOCCard The card
     */
    public function getCard($cardId) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card WHERE card_id = " .
            $cardId;
        $row = self::getObjectFromDB($sql);

        switch ($row["type"]) {
            case CARD_TYPE_ARTIST:
                return new AOCArtistCard($row);
            case CARD_TYPE_COMIC:
                return new AOCComicCard($row);
            case CARD_TYPE_RIPOFF:
                return new AOCRipoffCard($row);
            case CARD_TYPE_WRITER:
                return new AOCWriterCard($row);
            default:
                return new AOCCard($row);
        }
    }

    /**
     * Get the uiData for a specific card
     *
     * @param int $cardId The ID of the card
     * @param int $currentPlayerId The ID of the current player (The player viewing the card)
     * @return array The uiData for the card
     */
    public function getCardUiData($cardId, $currentPlayerId) {
        $card = $this->getCard($cardId);
        return $card->getUiData($currentPlayerId);
    }

    /**
     * Get all artist cards
     *
     * @return AOCArtistCard[] All artist cards
     */
    public function getArtistCards() {
        $rows = $this->getCardsByType(CARD_TYPE_ARTIST);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCArtistCard($row);
        }
        return $cards;
    }

    /**
     * Get all artist cards in the deck
     *
     * @return AOCArtistCard[] All artist cards in the deck
     */
    public function getArtistDeck() {
        $rows = $this->getDeckByType(CARD_TYPE_ARTIST);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCArtistCard($row);
        }
        return $cards;
    }

    /**
     * Get a count of remaining comics available for a specific genre
     *
     * @param int $genreId The ID of the genre
     * @return int The number of comics available for the genre
     */
    public function getAvailableComicCount($genreId) {
        $sql =
            "SELECT COUNT(*) FROM card WHERE card_type = " .
            CARD_TYPE_COMIC .
            " AND card_genre = " .
            $genreId .
            " AND card_owner = 0";
        $count = self::getUniqueValueFromDB($sql);
        return $count;
    }

    /**
     * Get all comic cards
     *
     * @return AOCComicCard[] All comic cards
     */
    public function getComicCards() {
        $rows = $this->getCardsByType(CARD_TYPE_COMIC);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCComicCard($row);
        }
        return $cards;
    }

    /**
     * Get all comic cards in the deck
     *
     * @return AOCComicCard[] All comic cards in the deck
     */
    public function getComicDeck() {
        $rows = $this->getDeckByType(CARD_TYPE_COMIC);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCComicCard($row);
        }
        return $cards;
    }

    /**
     * Get all comic cards in the deck, in descending order
     *
     * @return AOCComicCard[] All comic cards in the deck, in descending order
     */
    public function getComicDeckDesc() {
        $rows = $this->getDeckByTypeDesc(CARD_TYPE_COMIC);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCComicCard($row);
        }
        return $cards;
    }

    /**
     * Get all ripoff cards
     *
     * @return AOCRipoffCard[] All ripoff cards
     */
    public function getRipoffCards() {
        $rows = $this->getCardsByType(CARD_TYPE_RIPOFF);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCRipoffCard($row);
        }
        return $cards;
    }

    /**
     * Get all writer cards
     *
     * @return AOCWriterCard[] All writer cards
     */
    public function getWriterCards() {
        $rows = $this->getCardsByType(CARD_TYPE_WRITER);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCWriterCard($row);
        }
        return $cards;
    }

    /**
     * Get all writer cards in the deck
     *
     * @return AOCWriterCard[] All writer cards in the deck
     */
    public function getWriterDeck() {
        $rows = $this->getDeckByType(CARD_TYPE_WRITER);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCWriterCard($row);
        }
        return $cards;
    }

    /**
     * Get all cards in a player's hand
     *
     * @param int $playerId The ID of the player
     * @return AOCCard[] All cards in the player's hand
     */
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

    /**
     * Gets the uiData for a list of card objects
     *
     * @param AOCCard[] $cards An array of cards
     * @param int $currentPlayerId The ID of the current player (The player viewing the cards)
     * @return array The uiData for the cards
     */
    public function getCardsUiData($cards, $currentPlayerId) {
        $uiData = [];
        foreach ($cards as $card) {
            $uiData[] = $card->getUiData($currentPlayerId);
        }
        return $uiData;
    }

    /**
     * Get the uiData for all cards of a specific type
     *
     * @param int $cardType The type of card
     * @param int $currentPlayerId The ID of the current player
     * @return array The uiData for all cards of the specified type
     */
    public function getAllCardsUiData($cardType, $currentPlayerId) {
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

    /**
     * Get the uiData for all cards of a specific type in the deck
     *
     * @param int $cardType The type of card
     * @return array The uiData for all cards of the specified type in the deck
     */
    public function getDeckUiData($cardType) {
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
            $uiData[] = $card->getUiData(0);
        }
        return $uiData;
    }

    /**
     * Get the uiData for cards in a player's hand
     *
     * @param int $playerId The ID of the player
     * @param int $currentPlayerId The ID of the current player
     * @return array The uiData for cards in the player's hand
     */
    public function getHandUiData($playerId, $currentPlayerId) {
        $cards = $this->getPlayerHand($playerId);
        $uiData = [];
        foreach ($cards as $card) {
            $uiData[] = $card->getUiData($currentPlayerId);
        }
        return $uiData;
    }

    /**
     * Get the uiData for all cards in all players hand
     *
     * @param $players AOCPlayer[] An array of players
     * @param int $currentPlayerId The ID of the current player
     * @return array The uiData for all cards in all players hand
     */
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

    /**
     * Get the uiData for all cards in the supply
     *
     * @param int $cardType The type of card
     * @return array The uiData for all cards in the supply
     */
    public function getSupplyCardsUiData($cardType) {
        $cards = $this->getCardsByTypeInLocation($cardType, LOCATION_SUPPLY);
        $uiData = [];
        foreach ($cards as $card) {
            switch ($card["type"]) {
                case CARD_TYPE_ARTIST:
                    $uiData[] = (new AOCArtistCard($card))->getUiData(0);
                    break;
                case CARD_TYPE_COMIC:
                    $uiData[] = (new AOCComicCard($card))->getUiData(0);
                    break;
                case CARD_TYPE_WRITER:
                    $uiData[] = (new AOCWriterCard($card))->getUiData(0);
                    break;
            }
        }
        return $uiData;
    }

    /**
     * Shuffle the starting deck of a specific card type:
     * - Get all cards of the specified type that are in the void
     * - Shuffle the cards
     * - Set the location and locationArg for the cards
     * - Save the cards
     *
     * @param int $cardType The type of card
     * @return void
     */
    public function shuffleStartingDeck($cardType) {
        $rows = $this->getCardsByTypeInLocation($cardType, LOCATION_VOID);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCCard($row);
        }

        shuffle($cards);

        $locationArg = 0;
        foreach ($cards as $card) {
            $card->setLocation(LOCATION_DECK);
            $card->setLocationArg($locationArg);
            $locationArg++;
        }

        $this->saveCards($cards);
    }

    /**
     * Shuffle the discard pile of a specific card type:
     * - Get all cards of the specified type that are in the discard pile
     * - Shuffle the cards
     * - Set the location (deck) and locationArg (deck position) for the cards
     * - Save the cards
     *
     * @param int $cardType The type of card
     * @return void
     */
    public function shuffleDiscardPile($cardType) {
        $rows = $this->getCardsByTypeInLocation($cardType, LOCATION_DISCARD);

        $cards = [];
        foreach ($rows as $row) {
            $cards[] = new AOCCard($row);
        }

        shuffle($cards);

        $locationArg = 0;
        foreach ($cards as $card) {
            $card->setLocation(LOCATION_DECK);
            $card->setLocationArg($locationArg);
            $locationArg++;
            $this->saveCard($card);
        }
    }

    /**
     * Create the creative cards for a specific type
     *
     * @param int $creativeType The type of creative card
     * @return void
     */
    private function createCreativeCards($creativeType) {
        $sql =
            "INSERT INTO card (card_type, card_type_arg, card_genre, card_location) VALUES ";
        $values = [];

        $typeName = $creativeType == CARD_TYPE_ARTIST ? ARTIST : WRITER;

        foreach (GENRE_KEYS as $genreKey) {
            $values[] = "({$creativeType}, 10, {$genreKey}, -1)";
            $values[] = "({$creativeType}, 21, {$genreKey}, -1)";
            $values[] = "({$creativeType}, 22, {$genreKey}, -1)";
            $values[] = "({$creativeType}, 30, {$genreKey}, -1)";
        }

        $sql .= implode(", ", $values);
        self::DbQuery($sql);
    }

    /**
     * Create the comic cards
     *
     * @return void
     */
    private function createComicCards() {
        $sql =
            "INSERT INTO card (card_type, card_type_arg, card_genre, card_location) VALUES ";
        $values = [];
        foreach (COMIC_CARDS as $genreKey => $comics) {
            foreach ($comics as $bonusKey => $bonus) {
                $values[] =
                    "(" . CARD_TYPE_COMIC . ", {$bonusKey}, {$genreKey}, -1)";
            }
        }

        $sql .= implode(", ", $values);
        self::DbQuery($sql);
    }

    /**
     * Create the ripoff cards
     *
     * @return void
     */
    private function createRipoffCards() {
        $sql =
            "INSERT INTO card (card_type, card_type_arg, card_genre, card_location) VALUES ";
        $values = [];
        foreach (RIPOFF_CARDS as $genreKey => $ripoffs) {
            foreach ($ripoffs as $ripoffKey => $ripoff) {
                $values[] =
                    "(" . CARD_TYPE_RIPOFF . ", {$ripoffKey}, {$genreKey}, -1)";
            }
        }

        $sql .= implode(", ", $values);
        self::DbQuery($sql);
    }

    /**
     * Deal starting creative cards to each player
     *
     * @param AOCPlayer[] $players An array of players
     * @return void
     */
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

            // Set location and locationArg, then save
            $artistCard->setPlayerId($player->getId());
            $artistCard->setLocation(LOCATION_HAND);
            $artistCard->setLocationArg(
                $artistCard->getTypeId() * 100 +
                    $artistCard->getGenreId() +
                    $artistCard->getValue()
            );
            $this->saveCard($artistCard);

            // Set location and locationArg, then save
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

    /**
     * Get all cards of a specific type
     *
     * @param int $cardType The type of card
     * @return array All cards of the specified type
     */
    private function getCardsByType($cardType) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card WHERE card_type = " .
            $cardType;
        $rows = self::getObjectListFromDB($sql);

        return $rows;
    }

    /**
     * Get all cards of a specific type in a specific location
     *
     * @param int $cardType The type of card
     * @param int $location The location of the card
     * @return array All cards of the specified type in the specified location
     */
    private function getCardsByTypeInLocation($cardType, $location) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card WHERE card_type = " .
            $cardType .
            " AND card_location = " .
            $location;
        $rows = self::getObjectListFromDB($sql);

        return $rows;
    }

    /**
     * Get the card deck of a specific type
     *
     * @param int $cardType The type of card
     * @return array The card deck of the specified type
     */
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

    /**
     * Get the card deck of a specific type, in descending order
     *
     * @param int $cardType The type of card
     * @return array The card deck of the specified type, in descending order
     */
    private function getDeckByTypeDesc($cardType) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card WHERE card_type = " .
            $cardType .
            " AND card_location = " .
            LOCATION_DECK .
            " ORDER BY card_location_arg DESC";
        $rows = self::getObjectListFromDB($sql);

        return $rows;
    }

    /**
     * Save a card to the database
     *
     * @param AOCCard $card The card to save
     * @return void
     */
    public function saveCard($card) {
        $sql = "UPDATE card SET card_location = {$card->getLocation()}, card_location_arg = {$card->getLocationArg()}, card_owner = {$card->getPlayerId()} WHERE card_id = {$card->getId()}";
        self::DbQuery($sql);
    }

    /**
     * Save an array of cards to the database
     *
     * @param AOCCard[] $cards The cards to save
     * @return void
     */
    public function saveCards($cards) {
        foreach ($cards as $card) {
            $this->saveCard($card);
        }
    }
}
