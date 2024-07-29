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
        "args" => STATE_ARGS_NEXT_PLAYER_SETUP,
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
        "args" => STATE_ARGS_COMPLETE_SETUP,
        "action" => GAME_ACTION_COMPLETE_SETUP,
        "transitions" => ["startGame" => ST_START_NEW_ROUND],
    ],

    ST_START_NEW_ROUND => [
        "name" => START_NEW_ROUND,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_START_NEW_ROUND,
        "action" => GAME_ACTION_START_NEW_ROUND,
        "transitions" => [
            "increaseCreatives" => ST_ENTER_INCREASE_CREATIVES,
            "startActionsPhase" => ST_PLAYER_TURN,
        ],
    ],

    ST_ENTER_INCREASE_CREATIVES => [
        "name" => ENTER_INCREASE_CREATIVES,
        "description" => clienttranslate(
            "Waiting for other players to finish increasing creatives"
        ),
        "descriptionmyturn" => "",
        "type" => STATE_TYPE_MULTIPLE_ACTIVE_PLAYER,
        "initialprivate" => ST_INCREASE_CREATIVES,
        "args" => STATE_ARGS_ENTER_INCREASE_CREATIVES,
        "action" => GAME_ACTION_ENTER_INCREASE_CREATIVES,
        "transitions" => ["startActionsPhase" => ST_END_START_NEW_ROUND],
    ],

    ST_INCREASE_CREATIVES => [
        "name" => INCREASE_CREATIVES,
        "descriptionmyturn" => clienttranslate(
            '${you} may take a Learn or Train action on each printed comic'
        ),
        "type" => STATE_TYPE_PRIVATE,
        "args" => STATE_ARGS_INCREASE_CREATIVES,
        "possibleactions" => [
            PLAYER_ACTION_DOUBLE_TRAIN,
            PLAYER_ACTION_END_INCREASE_CREATIVES,
            PLAYER_ACTION_LEARN,
            PLAYER_ACTION_TRAIN,
        ],
        "transitions" => [
            "continue" => ST_INCREASE_CREATIVES,
            "finishIncreaseCreatives" => ST_ENTER_INCREASE_CREATIVES,
        ],
    ],

    ST_END_START_NEW_ROUND => [
        "name" => END_START_NEW_ROUND,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_END_START_NEW_ROUND,
        "action" => GAME_ACTION_END_START_NEW_ROUND,
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
        "updateGameProgression" => true,
        "possibleactions" => [PLAYER_ACTION_SELECT_ACTION_SPACE],
        "transitions" => [
            "nextPlayerTurn" => ST_NEXT_PLAYER,
            "performHire" => ST_PERFORM_HIRE,
            "performDevelop" => ST_PERFORM_DEVELOP,
            "performIdeas" => ST_PERFORM_IDEAS,
            "performPrint" => ST_PERFORM_PRINT,
            "performRoyalties" => ST_PERFORM_ROYALTIES,
            "performSales" => ST_PERFORM_SALES,
        ],
    ],

    ST_PERFORM_HIRE => [
        "name" => PERFORM_HIRE,
        "description" => clienttranslate('${actplayer} must hire ${hireText}'),
        "descriptionmyturn" => clienttranslate('${you} must hire ${hireText}'),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PERFORM_HIRE,
        "possibleactions" => [PLAYER_ACTION_HIRE_CREATIVE],
        "transitions" => [
            "performNextHire" => ST_PERFORM_HIRE,
            "discardCards" => ST_CHECK_HAND_SIZE,
            "performReassign" => ST_PERFORM_REASSIGN,
            "nextPlayerTurn" => ST_NEXT_PLAYER,
        ],
    ],

    ST_PERFORM_REASSIGN => [
        "name" => PERFORM_REASSIGN,
        "description" => clienttranslate(
            '${actplayer} may reassign one artist and one writer'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} may reassign one artist and one writer'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PERFORM_REASSIGN,
        "possibleactions" => [],
        "transitions" => ["nextPlayerTurn" => ST_NEXT_PLAYER],
    ],

    ST_PERFORM_DEVELOP => [
        "name" => PERFORM_DEVELOP,
        "description" => clienttranslate(
            '${actplayer} must develop a comic from the market ${fromDeckText}'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must develop a comic from the market ${fromDeckText}'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PERFORM_DEVELOP,
        "possibleactions" => [
            PLAYER_ACTION_DEVELOP_COMIC,
            PLAYER_ACTION_DEVELOP_FROM_GENRE,
        ],
        "transitions" => [
            "discardCards" => ST_CHECK_HAND_SIZE,
            "nextPlayerTurn" => ST_NEXT_PLAYER,
        ],
    ],

    ST_PERFORM_IDEAS => [
        "name" => PERFORM_IDEAS,
        "description" => clienttranslate(
            '${actplayer} must select ${ideasFromBoard} ideas from the board and 2 ideas from the supply'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must select ${ideasFromBoard} ideas from the board and 2 ideas from the supply'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PERFORM_IDEAS,
        "possibleactions" => [PLAYER_ACTION_CONFIRM_GAIN_IDEAS],
        "transitions" => ["nextPlayerTurn" => ST_NEXT_PLAYER],
    ],

    ST_PERFORM_PRINT => [
        "name" => PERFORM_PRINT,
        "description" => clienttranslate('${actplayer} ${descriptionText}'),
        "descriptionmyturn" => clienttranslate('${you} ${descriptionText}'),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PERFORM_PRINT,
        "possibleactions" => [
            PLAYER_ACTION_PRINT_COMIC,
            PLAYER_ACTION_SKIP_DOUBLE_PRINT,
        ],
        "transitions" => [
            "awardPrintBonus" => ST_PERFORM_PRINT_BONUS,
            "nextPlayerTurn" => ST_NEXT_PLAYER,
        ],
    ],

    ST_PERFORM_PRINT_BONUS => [
        "name" => PERFORM_PRINT_BONUS,
        "description" => clienttranslate(
            '${actplayer} must select 2 bonus ideas for printing ${comicName}'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must select 2 bonus ideas for printing ${comicName}'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PERFORM_PRINT_BONUS,
        "action" => GAME_ACTION_PERFORM_PRINT_BONUS,
        "possibleactions" => [PLAYER_ACTION_CONFIRM_GAIN_BONUS_IDEAS],
        "transitions" => ["checkMastery" => ST_PERFORM_PRINT_MASTERY],
    ],

    ST_PERFORM_PRINT_MASTERY => [
        "name" => PERFORM_PRINT_MASTERY,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_PERFORM_PRINT_MASTERY,
        "action" => GAME_ACTION_PERFORM_PRINT_MASTERY,
        "transitions" => ["checkUpgrade" => ST_PERFORM_PRINT_GET_UPGRADE_CUBE],
    ],

    ST_PERFORM_PRINT_GET_UPGRADE_CUBE => [
        "name" => PERFORM_PRINT_GET_UPGRADE_CUBE,
        "description" => clienttranslate(
            '${actplayer} may select an upgrade cube to relocate or skip getting an upgrade'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} may select an upgrade cube to relocate or skip getting an upgrade'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PERFORM_PRINT_GET_UPGRADE_CUBE,
        "action" => GAME_ACTION_PERFORM_PRINT_GET_UPGRADE_CUBE,
        "possibleactions" => [
            PLAYER_ACTION_SELECT_UPGRADE_CUBE,
            PLAYER_ACTION_SKIP_UPGRADE,
        ],
        "transitions" => [
            "continuePrint" => ST_PERFORM_PRINT_CONTINUE,
            "performPrintUpgrade" => ST_PERFORM_PRINT_UPGRADE,
        ],
    ],

    ST_PERFORM_PRINT_UPGRADE => [
        "name" => PERFORM_PRINT_UPGRADE,
        "description" => clienttranslate(
            '${actplayer} must select an action space to upgrade'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must select an action space to upgrade'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PERFORM_PRINT_UPGRADE,
        "possibleactions" => [PLAYER_ACTION_PLACE_UPGRADE_CUBE],
        "transitions" => [
            "continuePrint" => ST_PERFORM_PRINT_CONTINUE,
        ],
    ],

    ST_PERFORM_PRINT_CONTINUE => [
        "name" => PERFORM_PRINT_CONTINUE,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_PERFORM_PRINT_CONTINUE,
        "action" => GAME_ACTION_PERFORM_PRINT_CONTINUE,
        "transitions" => [
            "doublePrint" => ST_PERFORM_PRINT,
            "nextPlayerTurn" => ST_NEXT_PLAYER,
        ],
    ],

    ST_PERFORM_ROYALTIES => [
        "name" => PERFORM_ROYALTIES,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_PERFORM_ROYALTIES,
        "action" => GAME_ACTION_GAIN_ROYALITES,
        "transitions" => ["nextPlayerTurn" => ST_NEXT_PLAYER],
    ],

    ST_PERFORM_SALES => [
        "name" => PERFORM_SALES,
        "description" => clienttranslate(
            '${actplayer} can move their sales agent, and perform sales order flip and collect actions'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} can move your sales agent, and perform sales order flip and collect actions'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PERFORM_SALES,
        "possibleactions" => [
            PLAYER_ACTION_MOVE_SALES_AGENT,
            PLAYER_ACTION_MOVE_SALES_AGENT_WITH_TICKET,
            PLAYER_ACTION_FLIP_SALES_ORDER,
            PLAYER_ACTION_COLLECT_SALES_ORDER,
            PLAYER_ACTION_END_SALES,
        ],
        "transitions" => [
            "fulfillSalesOrder" => ST_PERFORM_SALES_FULFILL_ORDER,
            "continueSales" => ST_PERFORM_SALES_CONTINUE,
            "nextPlayerTurn" => ST_NEXT_PLAYER,
        ],
    ],

    ST_PERFORM_SALES_FULFILL_ORDER => [
        "name" => PERFORM_SALES_FULFILL_ORDER,
        "description" => clienttranslate(
            '${actplayer} must select a comic to fulfill the collected sales order'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must select a comic to fulfill the collected sales order'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_PERFORM_SALES_FULFILL_ORDER,
        "possibleactions" => [PLAYER_ACTION_SELECT_COMIC_FOR_ORDER],
        "transitions" => [
            "continueSales" => ST_PERFORM_SALES_CONTINUE,
        ],
    ],

    ST_PERFORM_SALES_CONTINUE => [
        "name" => PERFORM_SALES_CONTINUE,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_PERFORM_SALES_CONTINUE,
        "action" => GAME_ACTION_PERFORM_SALES_CONTINUE,
        "transitions" => ["continueSales" => ST_PERFORM_SALES],
    ],

    ST_CHECK_HAND_SIZE => [
        "name" => CHECK_HAND_SIZE,
        "description" => clienttranslate(
            '${actplayer} must discard ${numberToDiscard} card(s)'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must discard ${numberToDiscard} card(s)'
        ),
        "type" => STATE_TYPE_ACTIVE_PLAYER,
        "args" => STATE_ARGS_CHECK_HAND_SIZE,
        "possibleactions" => [PLAYER_ACTION_CONFIRM_DISCARD],
        "transitions" => ["nextPlayerTurn" => ST_NEXT_PLAYER],
    ],

    ST_NEXT_PLAYER => [
        "name" => NEXT_PLAYER,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_NEXT_PLAYER,
        "action" => GAME_ACTION_NEXT_PLAYER,
        "transitions" => [
            "nextPlayerTurn" => ST_PLAYER_TURN,
            "skipPlayer" => ST_NEXT_PLAYER,
            "endActionsPhase" => ST_ROUND_END_ESTABLISH_RANKING,
        ],
    ],

    ST_ROUND_END_ESTABLISH_RANKING => [
        "name" => ROUND_END_ESTABLISH_RANKING,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_ROUND_END_ESTABLISH_RANKING,
        "action" => GAME_ACTION_ROUND_END_ESTABLISH_RANKING,
        "transitions" => ["payEarnings" => ST_ROUND_END_PAY_EARNINGS],
    ],

    ST_ROUND_END_PAY_EARNINGS => [
        "name" => ROUND_END_PAY_EARNINGS,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_ROUND_END_PAY_EARNINGS,
        "action" => GAME_ACTION_ROUND_END_PAY_EARNINGS,
        "transitions" => [
            "establishPlayerOrder" => ST_ROUND_END_ESTABLISH_PLAYER_ORDER,
            "endGame" => ST_GAME_END,
        ],
    ],

    ST_ROUND_END_ESTABLISH_PLAYER_ORDER => [
        "name" => ROUND_END_ESTABLISH_PLAYER_ORDER,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_ROUND_END_ESTABLISH_PLAYER_ORDER,
        "action" => GAME_ACTION_ROUND_END_ESTABLISH_PLAYER_ORDER,
        "transitions" => ["subtractFans" => ST_ROUND_END_SUBTRACT_FANS],
    ],

    ST_ROUND_END_SUBTRACT_FANS => [
        "name" => ROUND_END_SUBTRACT_FANS,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_ROUND_END_SUBTRACT_FANS,
        "action" => GAME_ACTION_ROUND_END_SUBTRACT_FANS,
        "transitions" => ["removeEditors" => ST_ROUND_END_REMOVE_EDITORS],
    ],

    ST_ROUND_END_REMOVE_EDITORS => [
        "name" => ROUND_END_REMOVE_EDITORS,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_ROUND_END_REMOVE_EDITORS,
        "action" => GAME_ACTION_ROUND_END_REMOVE_EDITORS,
        "transitions" => ["refillCards" => ST_ROUND_END_REFILL_CARDS],
    ],

    ST_ROUND_END_REFILL_CARDS => [
        "name" => ROUND_END_REFILL_CARDS,
        "description" => "",
        "type" => STATE_TYPE_GAME,
        "args" => STATE_ARGS_ROUND_END_REFILL_CARDS,
        "action" => GAME_ACTION_ROUND_END_REFILL_CARDS,
        "transitions" => ["startNewRound" => ST_START_NEW_ROUND],
    ],

    ST_GAME_END => [
        "name" => GAME_END,
        "description" => clienttranslate("End of game"),
        "type" => STATE_TYPE_MANAGER,
        "action" => GAME_ACTION_GAME_END,
        "args" => STATE_ARGS_GAME_END,
    ],
];
