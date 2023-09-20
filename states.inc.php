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
 * states.inc.php
 *
 * AgeOfComics game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with "game" type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by "st" (ex: "stMyGameStateName").
   _ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in "nextState" PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on "onEnteringState" or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

//    !! It is not a good idea to modify this file when a game is running !!

$machinestates = [
    // The initial state. Please do not modify.
    ST_GAME_SETUP => [
        "name" => GAME_SETUP,
        "description" => "",
        "type" => STATE_TYPE_MANAGER,
        "action" => GAME_ACTION_GAME_SETUP,
        "transitions" => ["" => ST_PLAYER_SETUP],
    ],

    ST_PLAYER_SETUP => [
        "name" => PLAYER_SETUP,
        "description" => clienttranslate(
            '${actplayer} must select starting items'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must select starting items'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PLAYER_SETUP,
        "possibleactions" => [PLAYER_ACTION_SELECT_START_ITEMS],
        "transitions" => [
            "nextPlayerSetup" => ST_NEXT_PLAYER_SETUP,
        ],
    ],

    ST_NEXT_PLAYER_SETUP => [
        "name" => NEXT_PLAYER_SETUP,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "action" => GAME_ACTION_NEXT_PLAYER_SETUP,
        "transitions" => [
            "nextPlayerSetup" => ST_PLAYER_SETUP,
            "endPlayerSetup" => ST_COMPLETE_SETUP,
        ],
    ],

    ST_COMPLETE_SETUP => [
        "name" => COMPLETE_SETUP,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "action" => GAME_ACTION_COMPLETE_SETUP,
        "transitions" => ["startGame" => ST_START_NEW_ROUND],
    ],

    ST_START_NEW_ROUND => [
        "name" => START_NEW_ROUND,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "action" => GAME_ACTION_START_NEW_ROUND,
        "transitions" => ["startActionsPhase" => ST_PLAYER_TURN],
    ],

    ST_PLAYER_TURN => [
        "name" => PLAYER_TURN,
        "description" => clienttranslate(
            '${actplayer} must place an editor on an action space'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must place an editor on an action space'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PLAYER_TURN,
        "possibleactions" => [],
        "transitions" => ["nextPlayerTurn" => ST_GAME_END],
    ],

    ST_GAME_END => [
        "name" => GAME_END,
        "description" => clienttranslate("End of game"),
        "type" => STATE_TYPE_MANAGER,
        "action" => GAME_ACTION_GAME_END,
        "args" => STATE_ARGS_GAME_END,
    ],
];
