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

        // Deal starting creative cards
        $this->dealStartingCreative($players);
    }

    /**
     * Count the number of cards a player has in their hand
     *
     * @param int $playerId The ID of the player
     * @return int The number of cards in the player's hand
     */
    public function countCardsInPlayerHand($playerId) {
        $sql =
            "SELECT COUNT(*) FROM card WHERE card_owner = " .
            $playerId .
            " AND card_location = " .
            LOCATION_HAND;
        $count = self::getUniqueValueFromDB($sql);
        return $count;
    }

    /**
     * Send a specific card to its discard pile
     *
     * @param int $cardId The ID of the card being discarded
     * @return AOCArtistCard|AOCComicCard|AOCWriterCard The card that was discarded
     */
    public function discardCard($cardId) {
        $card = $this->getCard($cardId);

        // Get the number of cards in the discard pile (used for locationArg)
        $cardsInDiscard = count(
            $this->getCards($card->getTypeId(), null, LOCATION_DISCARD)
        );

        $card->setLocation(LOCATION_DISCARD);
        $card->setLocationArg($cardsInDiscard);
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
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId, card_display_value displayValue FROM card WHERE card_id = " .
            $cardId;
        $row = self::getObjectFromDB($sql);
        switch ($cardType) {
            case CARD_TYPE_ARTIST:
                $card = new AOCArtistCard($row);
                break;
            case CARD_TYPE_COMIC:
                $card = new AOCComicCard($row);
                break;
            case CARD_TYPE_WRITER:
                $card = new AOCWriterCard($row);
                break;
        }

        $card->setPlayerId($playerId);
        $card->setLocation(LOCATION_HAND);
        $card->setLocationArg($card->getHandSortOrder());

        $this->saveCard($card);

        return $card;
    }

    /**
     * Flags a comic as improved during the increase creatives phase.
     * This is used to track which comics have been improved during the phase, since they can only be improved once per phase.
     * Re-uses the displayValue field of the card to track this.(this value only matters for creative cards, so it's safe to use here)
     *
     * @param int $comicId The ID of the comic card
     * @return void
     */
    public function flagComicAsImproved($comicId) {
        $comicCard = $this->getCard($comicId);
        $comicCard->setDisplayValue(1);
        $this->saveCard($comicCard);
    }

    /**
     * Unflags all comics as improved during the increase creatives phase.
     * This is used to track which comics have been improved during the phase, since they can only be improved once per phase.
     * Re-uses the displayValue field of the card to track this.(this value only matters for creative cards, so it's safe to use here)
     *
     * @param int $playerId The ID of the player to unflag comics for
     * @return void
     */
    public function unflagComicsAsImproved($playerId) {
        $printedComics = $this->getPrintedComicsByPlayer($playerId);
        foreach ($printedComics as $comic) {
            $comic->setDisplayValue(0);
            $this->saveCard($comic);
        }
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
     * Get a specific card object from the database
     *
     * @param int $cardId The ID of the card
     * @return AOCArtistCard|AOCComicCard|AOCRipoffCard|AOCWriterCard The card
     */
    public function getCard($cardId) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId, card_display_value displayValue FROM card WHERE card_id = " .
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

    public function getComicCardMatchingMiniComic($miniComic) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId, card_display_value displayValue FROM card WHERE card_type = " .
            $miniComic->getTypeId() .
            " AND card_type_arg = " .
            $miniComic->getComicKey() .
            " AND card_genre = " .
            $miniComic->getGenreId() .
            " AND card_owner = " .
            $miniComic->getPlayerId();
        $row = self::getObjectFromDB($sql);
        return new AOCComicCard($row);
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
     * Get a list of cards from the database. If no parameters are provided, all cards will be returned.
     *
     * Optional filters:
     * - cardType: The type ID of card (CARD_TYPE_ARTIST, CARD_TYPE_COMIC, CARD_TYPE_RIPOFF, CARD_TYPE_WRITER)
     * - cardGenre: The genre key of the card (CRIME, HORROR, ROMANCE, SCIFI, SUPERHERO, WESTERN)
     * - cardLocation: The location of the card (ie. LOCATION_DECK, LOCATION_DISCARD, LOCATION_HAND)
     * - cardOwner: The ID of the player who owns the card
     * - orderBy: How to order the cards (ie. "card_location_arg DESC")
     *
     * @param int $cardType The type of card
     * @param int $cardGenre The genre of the card
     * @param int $cardLocation The location of the card
     * @param int $cardOwner The ID of the player who owns the card
     * @param string $orderBy The column to order by
     * @return AOCArtistCard[]|AOCComicCard[]|AOCRipoffCard[]|AOCWriterCard[] An array of cards
     */
    public function getCards(
        $cardType = null,
        $cardGenre = null,
        $cardLocation = null,
        $cardOwner = null,
        $orderBy = null
    ) {
        $operator = "WHERE";

        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId, card_display_value displayValue FROM card ";
        if ($cardType) {
            $sql .= " " . $operator . " card_type = " . $cardType;
            $operator = "AND";
        }
        if ($cardGenre) {
            $sql .= " " . $operator . " card_genre = " . $cardGenre;
            $operator = "AND";
        }
        if ($cardLocation) {
            $sql .= " " . $operator . " card_location = " . $cardLocation;
            $operator = "AND";
        }
        if ($cardOwner) {
            $sql .= " " . $operator . " card_owner = " . $cardOwner;
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
     * Get the uiData for a list of cards. If no parameters are provided, all cards will be returned.
     *
     * Optional filters:
     * - cardType: The type ID of card (CARD_TYPE_ARTIST, CARD_TYPE_COMIC, CARD_TYPE_RIPOFF, CARD_TYPE_WRITER)
     * - cardGenre: The genre key of the card (CRIME, HORROR, ROMANCE, SCIFI, SUPERHERO, WESTERN)
     * - cardLocation: The location of the card (ie. LOCATION_DECK, LOCATION_DISCARD, LOCATION_HAND)
     *
     * Note: The orderBy parameter is not needed because the cards will be sorted by locationArg in the UI
     *
     * @param int $currentPlayerId The ID of the current player (The player viewing the card)
     * @param int $cardType The type of card
     * @param int $cardGenre The genre of the card
     * @param int $cardLocation The location of the card
     * @return array An array of uiData for the cards
     */
    public function getCardsUiData(
        $currentPlayerId,
        $cardType = null,
        $cardGenre = null,
        $cardLocation = null,
        $cardOwner = null
    ) {
        $cards = $this->getCards(
            $cardType,
            $cardGenre,
            $cardLocation,
            $cardOwner
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
     * Gets the ripoffs that a player can print.
     * A player can print a ripoff if another player had printed the original comic.
     *
     * @param int $playerId The ID of the player printing the ripoff
     * @return AOCRipoffCard[] An array of ripoff cards that the player can print
     */
    public function getPrintableRipoffsByPlayer($playerId) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId, card_display_value displayValue FROM card WHERE NOT card_owner = " .
            $playerId .
            " AND card_location = " .
            LOCATION_PLAYER_MAT .
            " AND card_type = " .
            CARD_TYPE_COMIC;

        $rows = self::getObjectListFromDB($sql);

        $printedOriginals = [];
        foreach ($rows as $row) {
            $printedOriginals[] = new AOCComicCard($row);
        }

        $printableRipoffs = [];

        foreach ($printedOriginals as $original) {
            $sql =
                "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId, card_display_value displayValue FROM card WHERE card_type = " .
                CARD_TYPE_RIPOFF .
                " AND card_type_arg = " .
                $original->getBonus() .
                " AND card_genre = " .
                $original->getGenreId() .
                " AND card_location = -1";
            $row = self::getObjectFromDB($sql);
            if ($row) {
                $printableRipoffs[] = new AOCRipoffCard($row);
            }
        }

        return $printableRipoffs;
    }

    /**
     * Gets all of the comics (including ripoffs) printed by a player.
     *
     * @param int $playerId The ID of the player
     * @return AOCComicCard[]|AOCRipoffCard[] An array of comic cards printed by the player
     */
    public function getPrintedComicsByPlayer($playerId) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId, card_display_value displayValue FROM card WHERE card_owner = " .
            $playerId .
            " AND card_location = " .
            LOCATION_PLAYER_MAT .
            " AND (card_type = " .
            CARD_TYPE_COMIC .
            " OR card_type = " .
            CARD_TYPE_RIPOFF .
            ")";

        $rows = self::getObjectListFromDB($sql);

        $cards = [];
        foreach ($rows as $row) {
            switch ($row["type"]) {
                case CARD_TYPE_COMIC:
                    $cards[] = new AOCComicCard($row);
                    break;
                case CARD_TYPE_RIPOFF:
                    $cards[] = new AOCRipoffCard($row);
                    break;
            }
        }

        return $cards;
    }

    /**
     * Gets the artist card for a printed comic.
     *
     * @param int $playerId The ID of the player
     * @param int $comicSlot The slot of the comic on the player mat
     * @return AOCArtistCard The artist card for the printed comic
     */
    public function getArtistCardForPrintedComic($playerId, $comicSlot) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId, card_display_value displayValue FROM card WHERE card_owner = " .
            $playerId .
            " AND card_location = " .
            LOCATION_PLAYER_MAT .
            " AND card_type = " .
            CARD_TYPE_ARTIST .
            " AND card_location_arg = " .
            $comicSlot;

        $row = self::getObjectFromDB($sql);
        return new AOCArtistCard($row);
    }

    /**
     * Gets the writer card for a printed comic.
     *
     * @param int $playerId The ID of the player
     * @param int $comicSlot The slot of the comic on the player mat
     * @return AOCWriterCard The writer card for the printed comic
     */
    public function getWriterCardForPrintedComic($playerId, $comicSlot) {
        $sql =
            "SELECT card_id id, card_type type, card_type_arg typeArg, card_genre genre, card_location location, card_location_arg locationArg, card_owner playerId, card_display_value displayValue FROM card WHERE card_owner = " .
            $playerId .
            " AND card_location = " .
            LOCATION_PLAYER_MAT .
            " AND card_type = " .
            CARD_TYPE_WRITER .
            " AND card_location_arg = " .
            $comicSlot;

        $row = self::getObjectFromDB($sql);
        return new AOCWriterCard($row);
    }

    /**
     * Increase the display value of a creative card by 1
     * Used during the increase creatives phase
     *
     * @param AOCCard $card The card to improve
     * @return void
     */
    public function improveCreativeCard($card) {
        $card->setDisplayValue($card->getDisplayValue() + 1);
        $this->saveCard($card);
    }

    /**
     * Save a card to the database
     *
     * @param AOCCard $card The card to save
     * @return void
     */
    public function saveCard($card) {
        $sql = "UPDATE card SET card_location = {$card->getLocation()}, card_location_arg = {$card->getLocationArg()}, card_owner = {$card->getPlayerId()}, card_display_value = {$card->getDisplayValue()} WHERE card_id = {$card->getId()}";
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
        $rows = $this->getCards($cardType, null, LOCATION_DISCARD);

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
            "INSERT INTO card (card_type, card_type_arg, card_genre, card_location, card_display_value) VALUES ";
        $values = [];

        // For each genre, create:
        // - 1 level 1 card
        // - 2 level 2 cards
        // - 1 level 3 card
        foreach (GENRE_KEYS as $genreKey) {
            $values[] = "({$creativeType}, 10, {$genreKey}, -1, 1)";
            $values[] = "({$creativeType}, 21, {$genreKey}, -1, 2)";
            $values[] = "({$creativeType}, 22, {$genreKey}, -1, 2)";
            $values[] = "({$creativeType}, 30, {$genreKey}, -1, 3)";
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
        // Get all artist and writer cards
        $artistCards = $this->getCards(CARD_TYPE_ARTIST);
        $writerCards = $this->getCards(CARD_TYPE_WRITER);

        // Filter to level 2 cards
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

            // Set playerId, location and locationArg. Then save.
            $artistCard->setPlayerId($player->getId());
            $artistCard->setLocation(LOCATION_HAND);
            $artistCard->setLocationArg($artistCard->getHandSortOrder());
            $this->saveCard($artistCard);

            // Set playerId, location and locationArg. Then save.
            $writerCard->setPlayerId($player->getId());
            $writerCard->setLocation(LOCATION_HAND);
            $writerCard->setLocationArg($writerCard->getHandSortOrder());
            $this->saveCard($writerCard);
        }
    }
}
