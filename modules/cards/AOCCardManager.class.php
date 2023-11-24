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
     * @return AOCArtistCard|AOCComicCard|AOCWriterCard The card that was discarded
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
     * @return AOCArtistCard|AOCComcCard|AOCWriterCard The card that was drawn
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
     * Get a card object from the database
     *
     * @param int $cardId The ID of the card
     * @return AOCArtistCard|AOCComicCard|AOCRipoffCard|AOCWriterCard The card
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
     * Get all cards of a specific type (optionally sorted and/or filtered by genre and/or location)
     *
     * @param int $cardType The type of card
     * @param int|null $cardGenre The genre of the card, if any
     * @param int|null $cardLocation The location of the card, if any
     * @param string|null $orderBy The order to sort the cards, if any
     * @return AOCArtsitCard[]|AOCComicCard[]|AOCRipoffCard[]|AOCWriterCard[] All cards of the specified type
     */
    public function getCardsOfType(
        $cardType,
        $cardGenre = null,
        $cardLocation = null,
        $orderBy = null
    ) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId FROM card WHERE card_type = " .
            $cardType;
        if ($cardGenre) {
            $sql .= " AND card_genre = " . $cardGenre;
        }
        if ($cardLocation) {
            $sql .= " AND card_location = " . $cardLocation;
        }
        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy;
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

    /**
     * Get the uiData for all cards of a specific type (optionally sorted and/or filtered by genre and/or location)
     *
     * @param int $cardType The type of card
     * @param int $currentPlayerId The ID of the current player
     * @param int|null $cardGenre The genre of the card, if any
     * @param int|null $cardLocation The location of the card, if any
     * @param string|null $orderBy The order to sort the cards, if any
     * @return array The uiData for all cards of the specified type
     */
    public function getCardsOfTypeUiData(
        $cardType,
        $currentPlayerId,
        $cardGenre = null,
        $cardLocation = null,
        $orderBy = null
    ) {
        $cards = $this->getCardsOfType(
            $cardType,
            $cardGenre,
            $cardLocation,
            $orderBy
        );

        $uiData = [];
        foreach ($cards as $card) {
            $uiData[] = $card->getUiData($currentPlayerId);
        }
        return $uiData;
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
        $rows = $this->getCardsOfType($cardType, null, LOCATION_DISCARD);

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
        $artistCards = $this->getCardsOfType(CARD_TYPE_ARTIST);
        $writerCards = $this->getCardsOfType(CARD_TYPE_WRITER);

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
}
