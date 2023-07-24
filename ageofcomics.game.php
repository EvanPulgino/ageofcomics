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
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();

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
        ]);

        // Genres in the game
        $this->genres = [
            GENRE_CRIME,
            GENRE_HORROR,
            GENRE_ROMANCE,
            GENRE_SCIFI,
            GENRE_SUPERHERO,
            GENRE_WESTERN,
        ];

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
        $this->salesOrderManager = new AOCSalesOrderManager($this);
    }

    protected function getGameName() {
        // Used for translations and stuff. Please do not modify.
        return "ageofcomics";
    }

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
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
        self::setGameStateInitialValue(CURRENT_ROUND, 0);
        self::setGameStateInitialValue(IDEAS_SPACE_CRIME, 1);
        self::setGameStateInitialValue(IDEAS_SPACE_HORROR, 1);
        self::setGameStateInitialValue(IDEAS_SPACE_ROMANCE, 1);
        self::setGameStateInitialValue(IDEAS_SPACE_SCIFI, 1);
        self::setGameStateInitialValue(IDEAS_SPACE_SUPERHERO, 1);
        self::setGameStateInitialValue(IDEAS_SPACE_WESTERN, 1);

        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //self::initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        //self::initStat( 'player', 'player_teststat1', 0 );  // Init a player statistics (for all players)

        // Setup the initial game situation here
        $this->calendarManager->setupNewGame();
        $this->editorManager->setupNewGame($aocPlayers);
        $this->masteryManager->setupNewGame();
        $this->salesOrderManager->setupNewGame(sizeof($aocPlayers));
        $this->cardManager->setupNewGame();

        // Activate first player (which is in general a good idea :) )
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
        $current_player_id = self::getCurrentPlayerId(); // !! We must only return informations visible by this player !!

        $gamedata = [
            "artistCards" => $this->cardManager->getCardsUiData(
                CARD_TYPE_ARTIST
            ),
            "calendarTiles" => $this->calendarManager->getCalendarTilesUiData(),
            "comicCards" => $this->cardManager->getCardsUiData(CARD_TYPE_COMIC),
            "constants" => get_defined_constants(true)["user"],
            "editors" => $this->editorManager->getEditorsUiData(),
            "mastery" => $this->masteryManager->getMasteryTokensUiData(),
            "playerInfo" => $this->playerManager->getPlayersUiData(),
            "ripoffCards" => $this->cardManager->getCardsUiData(
                CARD_TYPE_RIPOFF
            ),
            "salesOrders" => $this->salesOrderManager->getSalesOrdersUiData(),
            "writerCards" => $this->cardManager->getCardsUiData(
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

    //////////////////////////////////////////////////////////////////////////////
    //////////// Utility functions
    ////////////

    /**
     * Public wrapper for getCurrentPlayerId()
     * @return mixed
     */
    function getViewingPlayerId() {
        return self::getCurrentPlayerId();
    }

    //////////////////////////////////////////////////////////////////////////////
    //////////// Player actions
    ////////////

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in ageofcomics.action.php)
    */

    /*
    
    Example:

    function playCard( $card_id )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'playCard' ); 
        
        $player_id = self::getActivePlayerId();
        
        // Add your game logic to play a card there 
        ...
        
        // Notify all players about the card played
        self::notifyAllPlayers( "cardPlayed", clienttranslate( '${player_name} plays ${card_name}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'card_name' => $card_name,
            'card_id' => $card_id
        ) );
          
    }
    
    */

    //////////////////////////////////////////////////////////////////////////////
    //////////// Game state arguments
    ////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    /*
    
    Example for game state "MyGameState":
    
    function argMyGameState()
    {
        // Get some values from the current game situation in database...
    
        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }    
    */

    //////////////////////////////////////////////////////////////////////////////
    //////////// Game state actions
    ////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */

    /*
    
    Example for game state "MyGameState":

    function stMyGameState()
    {
        // Do some stuff ...
        
        // (very often) go to another gamestate
        $this->gamestate->nextState( 'some_gamestate_transition' );
    }    
    */

    //////////////////////////////////////////////////////////////////////////////
    //////////// Zombie
    ////////////

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

    ///////////////////////////////////////////////////////////////////////////////////:
    ////////// DB upgrade
    //////////

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
