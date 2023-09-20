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
        ]);

        // Initialize action managers
        $this->gameStateActions = new AOCGameStateActions($this);
        $this->playerActions = new AOCPlayerActions($this);

        // Initialize player manager
        $this->playerManager = new AOCPlayerManager($this);

        // Initialize component managers
        $this->cardManager = new AOCCardManager($this);
        $this->calendarManager = new AOCCalendarManager($this);
        $this->editorManager = new AOCEditorManager($this);
        $this->masteryManager = new AOCMasteryManager($this);
        $this->miniComicManager = new AOCMiniComicManager($this);
        $this->salesOrderManager = new AOCSalesOrderManager($this);
    }

    protected function getGameName() {
        // Used for translations and stuff. Please do not modify.
        return "ageofcomics";
    }

    /**
     * @param mixed $players
     * @param mixed $options
     * @return void
     *
     * This method is called only once, when a new game is launched.
     * In this method, you must setup the game according to the game rules, so that
     * the game is ready to be played.
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
        self::setGameStateInitialValue(MAX_ACTION_SPACES, sizeof($aocPlayers) + 1);
        self::setGameStateInitialValue(CURRENT_ROUND, 0);
        self::setGameStateInitialValue(TICKET_SUPPLY, 4);
        self::setGameStateInitialValue(START_IDEAS, 2);
        self::setGameStateInitialValue(
            CARD_SUPPLY_SIZE,
            sizeof($aocPlayers) == 4 ? 4 : 3
        );
        foreach (GENRES as $genreId => $genreName) {
            self::setGameStateInitialValue("ideas_space_{$genreName}", 1);
        }

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

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas() {
        $currentPlayerId = self::getCurrentPlayerId();

        $gamedata = [
            "artistCards" => $this->cardManager->getCardsUiData(
                CARD_TYPE_ARTIST,
                $currentPlayerId
            ),
            "artistDeck" => $this->cardManager->getDeckUiData(CARD_TYPE_ARTIST),
            "artistSupply" => $this->cardManager->getSupplyCardsUiData(
                CARD_TYPE_ARTIST
            ),
            "calendarTiles" => $this->calendarManager->getCalendarTilesUiData(),
            "comicCards" => $this->cardManager->getCardsUiData(
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
            "ripoffCards" => $this->cardManager->getCardsUiData(
                CARD_TYPE_RIPOFF,
                $currentPlayerId
            ),
            "salesOrders" => $this->salesOrderManager->getSalesOrdersUiData(),
            "ticketSupply" => self::getGameStateValue(TICKET_SUPPLY),
            "writerCards" => $this->cardManager->getCardsUiData(
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

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression() {
        return (self::getGameStateValue(TURNS_TAKEN) /
            self::getGameStateValue(TOTAL_TURNS)) *
            100;
    }

    function __call($name, $args) {
        if (in_array($name, get_class_methods($this->gameStateActions))) {
            call_user_func([$this->gameStateActions, $name], $args);
        } elseif (in_array($name, get_class_methods($this->playerActions))) {
            call_user_func([$this->playerActions, $name], $args);
        }
    }

    /**
     * Get current contents of ideas space
     * @return array
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
     * Public wrapper for getCurrentPlayerId()
     * @return mixed
     */
    function getViewingPlayerId() {
        return self::getCurrentPlayerId();
    }

    function argsPlayerSetup() {
        return [
            "startIdeas" => self::getGameStateValue(START_IDEAS),
        ];
    }

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
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

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
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
