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
 * ageofcomics.game.php
 *
 * This is the main file for your game logic.
 *
 * In this PHP file, you are going to defines the rules of the game.
 *
 * @link https://en.doc.boardgamearena.com/Main_game_logic:_yourgamename.game.php
 *
 * @EvanPulgino
 */

include "modules/autoload.php";
require_once APP_GAMEMODULE_PATH . "module/table/table.game.php";
require_once "modules/AOCConstants.inc.php";
class AgeOfComics extends Table {
    function __construct() {
        parent::__construct();

        // Create game state labels
        self::initGameStateLabels([
            TOTAL_TURNS => 10,
            TURNS_TAKEN => 11,
            CURRENT_ROUND => 12,
            IDEAS_SPACE_CRIME => 13,
            IDEAS_SPACE_HORROR => 14,
            IDEAS_SPACE_ROMANCE => 15,
            IDEAS_SPACE_SCIFI => 16,
            IDEAS_SPACE_SUPERHERO => 17,
            IDEAS_SPACE_WESTERN => 18,
            TICKET_SUPPLY => 19,
            START_IDEAS => 20,
            CARD_SUPPLY_SIZE => 21,
            MAX_ACTION_SPACES => 22,
            SELECTED_ACTION_SPACE => 23,
            CAN_HIRE_ARTIST => 24,
            CAN_HIRE_WRITER => 25,
        ]);

        // Initialize player manager
        $this->playerManager = new AOCPlayerManager($this);

        // Initialize component managers
        $this->cardManager = new AOCCardManager($this);
        $this->calendarManager = new AOCCalendarManager($this);
        $this->editorManager = new AOCEditorManager($this);
        $this->masteryManager = new AOCMasteryManager($this);
        $this->miniComicManager = new AOCMiniComicManager($this);
        $this->salesOrderManager = new AOCSalesOrderManager($this);

        // Initialize states
        $this->states[CHECK_HAND_SIZE] = new AOCCheckHandSizeState($this);
        $this->states[COMPLETE_SETUP] = new AOCCompleteSetupState($this);
        $this->states[NEXT_PLAYER] = new AOCNextPlayerState($this);
        $this->states[NEXT_PLAYER_SETUP] = new AOCNextPlayerSetupState($this);
        $this->states[PERFORM_DEVELOP] = new AOCPerformDevelopState($this);
        $this->states[PERFORM_HIRE] = new AOCPerformHireState($this);
        $this->states[PERFORM_IDEAS] = new AOCPerformIdeasState($this);
        $this->states[PERFORM_PRINT] = new AOCPerformPrintState($this);
        $this->states[PERFORM_SALES] = new AOCPerformSalesState($this);
        $this->states[PLAYER_SETUP] = new AOCPlayerSetupState($this);
        $this->states[PLAYER_TURN] = new AOCPlayerTurnState($this);
        $this->states[START_NEW_ROUND] = new AOCStartNewRoundState($this);
    }

    protected function getGameName() {
        // Used for translations and stuff. Please do not modify.
        return "ageofcomics";
    }

    /**
     * This method is called only once, when a new game is launched.
     * In this method the initial game setup is performed.
     *
     * @param mixed $players Array of players
     * @param mixed $options Array of game options
     * @return void
     */
    protected function setupNewGame($players, $options = []) {
        // Setup players
        $this->playerManager->setupNewGame($players);
        // Get player objects for rest of setup
        $aocPlayers = $this->playerManager->getPlayers();

        /************ Start the game initialization *****/

        // Init global values with their initial values
        self::setGameStateInitialValue(TOTAL_TURNS, sizeof($aocPlayers) * 20);
        self::setGameStateInitialValue(TURNS_TAKEN, 0);
        self::setGameStateInitialValue(
            MAX_ACTION_SPACES,
            sizeof($aocPlayers) + 1
        );
        self::setGameStateInitialValue(CURRENT_ROUND, 0);
        self::setGameStateInitialValue(TICKET_SUPPLY, 4);
        self::setGameStateInitialValue(START_IDEAS, 2);
        self::setGameStateInitialValue(SELECTED_ACTION_SPACE, 0);
        self::setGameStateInitialValue(
            CARD_SUPPLY_SIZE,
            sizeof($aocPlayers) == 4 ? 4 : 3
        );
        foreach (GENRES as $genreId => $genreName) {
            self::setGameStateInitialValue("ideas_space_{$genreName}", 1);
        }
        self::setGameStateInitialValue(CAN_HIRE_ARTIST, 0);
        self::setGameStateInitialValue(CAN_HIRE_WRITER, 0);

        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //self::initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        //self::initStat( 'player', 'player_teststat1', 0 );  // Init a player statistics (for all players)

        // Setup the initial game situation here
        $this->calendarManager->setupNewGame();
        $this->editorManager->setupNewGame($aocPlayers);
        $this->masteryManager->setupNewGame();
        $this->miniComicManager->setupNewGame();
        $this->salesOrderManager->setupNewGame(sizeof($aocPlayers));
        $this->cardManager->setupNewGame($aocPlayers);

        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    /**
     * Gathers all information about current game situation (visible by the current player).
     *
     * The method is called each time the game interface is displayed to a player, ie:
     * - when the game starts
     * - when a player refreshes the game page (F5)
     *
     * @return array Array containing all the current game information that must be sent to the client
     */
    protected function getAllDatas() {
        $currentPlayerId = self::getCurrentPlayerId();

        $gamedata = [
            "artistCards" => $this->cardManager->getAllCardsUiData(
                CARD_TYPE_ARTIST,
                $currentPlayerId
            ),
            "artistDeck" => $this->cardManager->getDeckUiData(CARD_TYPE_ARTIST),
            "artistSupply" => $this->cardManager->getSupplyCardsUiData(
                CARD_TYPE_ARTIST
            ),
            "calendarTiles" => $this->calendarManager->getCalendarTilesUiData(),
            "comicCards" => $this->cardManager->getAllCardsUiData(
                CARD_TYPE_COMIC,
                $currentPlayerId
            ),
            "comicDeck" => $this->cardManager->getDeckUiData(CARD_TYPE_COMIC),
            "comicSupply" => $this->cardManager->getSupplyCardsUiData(
                CARD_TYPE_COMIC
            ),
            "constants" => get_defined_constants(true)["user"],
            "editors" => $this->editorManager->getEditorsUiData(),
            "ideasSpaceContents" => $this->getIdeasSpaceContents(),
            "mastery" => $this->masteryManager->getMasteryTokensUiData(),
            "miniComics" => $this->miniComicManager->getMiniComicsUiData(),
            "playerHands" => $this->cardManager->getPlayerHandsUiData(
                $this->playerManager->getPlayers(),
                $currentPlayerId
            ),
            "playerInfo" => $this->playerManager->getPlayersUiData(),
            "ripoffCards" => $this->cardManager->getAllCardsUiData(
                CARD_TYPE_RIPOFF,
                $currentPlayerId
            ),
            "salesOrders" => $this->salesOrderManager->getSalesOrdersUiData(),
            "ticketSupply" => self::getGameStateValue(TICKET_SUPPLY),
            "writerCards" => $this->cardManager->getAllCardsUiData(
                CARD_TYPE_WRITER,
                $currentPlayerId
            ),
            "writerDeck" => $this->cardManager->getDeckUiData(CARD_TYPE_WRITER),
            "writerSupply" => $this->cardManager->getSupplyCardsUiData(
                CARD_TYPE_WRITER
            ),
        ];

        return $gamedata;
    }

    /**
     * Compute and return the current game progression.
     * The number returned must be an integer beween 0 (= the game just started) and
     * 100 (= the game is finished or almost finished).
     *
     * This method is called each time we are in a game state with the "updateGameProgression" property set to true
     *
     * @link https://en.doc.boardgamearena.com/Your_game_state_machine:_states.inc.php#updateGameProgression
     *
     * @return int The percentage of the game completed rounded to nearest integer
     */
    function getGameProgression() {
        return (self::getGameStateValue(TURNS_TAKEN) /
            self::getGameStateValue(TOTAL_TURNS)) *
            100;
    }

    /**
     * This method is called everytime the system tries to call an undefined method.
     * It will look for functions that are defined in the states and call them if they exist:
     *
     * @param string $name The name of the function being called
     * @param array $args The arguments passed to the function
     * @return void
     */
    function __call($name, $args) {
        foreach ($this->states as $state) {
            if (in_array($name, get_class_methods($state))) {
                call_user_func([$state, $name], $args);
            }
        }
    }

    /**
     * Gets the current contents of the ideas space
     *
     * @return array Array of contents of the Ideas space on the board
     */
    function getIdeasSpaceContents() {
        $ideasSpaceContents = [];
        foreach (GENRES as $genreId => $genreName) {
            $ideasSpaceContents[$genreId] = self::getGameStateValue(
                "ideas_space_{$genreName}"
            );
        }

        return $ideasSpaceContents;
    }

    /**
     * Gets the player id of the player viewing the game
     *
     * This is a wrapper for the getCurrentPlayerId function so it can be accessed outside of the class
     *
     * @return int The id of the player viewing the game
     */
    function getViewingPlayerId() {
        return self::getCurrentPlayerId();
    }

    function argsCheckHandSize() {
        return $this->states[CHECK_HAND_SIZE]->getArgs();
    }
    function argsCompleteSetup() {
        return $this->states[COMPLETE_SETUP]->getArgs();
    }
    function argsNextPlayer() {
        return $this->states[NEXT_PLAYER]->getArgs();
    }
    function argsNextPlayerSetup() {
        return $this->states[NEXT_PLAYER_SETUP]->getArgs();
    }
    function argsPerformDevelop() {
        return $this->states[PERFORM_DEVELOP]->getArgs();
    }
    function argsPerformHire() {
        return $this->states[PERFORM_HIRE]->getArgs();
    }
    function argsPerformIdeas() {
        return $this->states[PERFORM_IDEAS]->getArgs();
    }
    function argsPerformPrint() {
        return $this->states[PERFORM_PRINT]->getArgs();
    }
    function argsPerformSales() {
        return $this->states[PERFORM_SALES]->getArgs();
    }
    function argsPlayerSetup() {
        return $this->states[PLAYER_SETUP]->getArgs();
    }
    function argsPlayerTurn() {
        return $this->states[PLAYER_TURN]->getArgs();
    }
    function argsStartNewRound() {
        return $this->states[START_NEW_ROUND]->getArgs();
    }

    /**
     * Called when it is the turn of a player who has quit the game
     *
     * @param array $state The current game state
     * @param int $active_player The id of the active player
     * @return void
     */
    function zombieTurn($state, $active_player) {
        $statename = $state["name"];

        if ($state["type"] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState("zombiePass");
                    break;
            }

            return;
        }

        if ($state["type"] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive($active_player, "");

            return;
        }

        throw new feException(
            "Zombie mode not supported at this game state: " . $statename
        );
    }

    /**
     * Called when system detects a game running with an old database schema.
     * Updates schema to match current version.
     *
     * @param int $from_version The current version of this game database, in numerical form
     * @return void
     */
    function upgradeTableDb($from_version) {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345

        // Example:
        //        if( $from_version <= 1404301345 )
        //        {
        //            // ! important ! Use DBPREFIX_<table_name> for all tables
        //
        //            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
        //            self::applyDbUpgradeToAllDB( $sql );
        //        }
        //        if( $from_version <= 1405061421 )
        //        {
        //            // ! important ! Use DBPREFIX_<table_name> for all tables
        //
        //            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
        //            self::applyDbUpgradeToAllDB( $sql );
        //        }
        //        // Please add your future database scheme changes here
        //
        //
    }
}
