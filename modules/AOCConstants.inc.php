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
 * AOCConstants.inc.php
 *
 */

/** Actions */
define("GAME_ACTION_GAME_END", "stGameEnd");
define("GAME_ACTION_GAME_SETUP", "stGameSetup");

/** Genre Keys */
define("GENRE_CRIME", 10);
define("GENRE_HORROR", 20);
define("GENRE_ROMANCE", 30);
define("GENRE_SCIFI", 40);
define("GENRE_SUPERHERO", 50);
define("GENRE_WESTERN", 60);

/** Genre Names */
define("CRIME", "crime");
define("HORROR", "horror");
define("ROMANCE", "romance");
define("SCIFI", "scifi");
define("SUPERHERO", "superhero");
define("WESTERN", "western");

/** Global Variables */
define("CURRENT_ROUND", "current_round");
define("IDEAS_SPACE_CRIME", "ideas_space_crime");
define("IDEAS_SPACE_HORROR", "ideas_space_horror");
define("IDEAS_SPACE_ROMANCE", "ideas_space_romance");
define("IDEAS_SPACE_SCIFI", "ideas_space_scifi");
define("IDEAS_SPACE_SUPERHERO", "ideas_space_superhero");
define("IDEAS_SPACE_WESTERN", "ideas_space_western");
define("TOTAL_TURNS", "total_turns");
define("TURNS_TAKEN", "turns_taken");

/** Locations */
define("LOCATION_PLAYER", 1);
define("LOCATION_SUPPLY", 2);
define("LOCATION_ACTION_SPACE_1", 1);
define("LOCATION_ACTION_SPACE_2", 2);
define("LOCATION_ACTION_SPACE_3", 3);
define("LOCATION_ACTION_SPACE_4", 4);
define("LOCATION_ACTION_SPACE_5", 5);
define("LOCATION_ACTION_HIRE", 10);
define("LOCATION_ACTION_DEVELOP", 20);
define("LOCATION_ACTION_IDEAS", 30);
define("LOCATION_ACTION_PRINT", 40);
define("LOCATION_ACTION_ROYALTIES", 50);
define("LOCATION_ACTION_SALES", 60);
define("LOCATION_AGENT_SPACE_1_1", 101);
define("LOCATION_AGENT_SPACE_1_2", 102);
define("LOCATION_AGENT_SPACE_1_3", 103);
define("LOCATION_AGENT_SPACE_1_4", 104);
define("LOCATION_AGENT_SPACE_2_1", 201);
define("LOCATION_AGENT_SPACE_2_2", 202);
define("LOCATION_AGENT_SPACE_2_3", 203);
define("LOCATION_AGENT_SPACE_2_4", 204);
define("LOCATION_AGENT_SPACE_3_1", 301);
define("LOCATION_AGENT_SPACE_3_2", 302);
define("LOCATION_AGENT_SPACE_3_3", 303);
define("LOCATION_AGENT_SPACE_3_4", 304);
define("LOCATION_AGENT_SPACE_4_1", 401);
define("LOCATION_AGENT_SPACE_4_2", 402);
define("LOCATION_AGENT_SPACE_4_3", 403);
define("LOCATION_AGENT_SPACE_4_4", 404);
define("LOCATION_AGENT_SPACE_5_1", 501);
define("LOCATION_AGENT_SPACE_5_2", 502);
define("LOCATION_AGENT_SPACE_5_3", 503);
define("LOCATION_AGENT_SPACE_5_4", 504);
define("LOCATION_AGENT_SPACE_6_1", 601);
define("LOCATION_AGENT_SPACE_6_2", 602);
define("LOCATION_AGENT_SPACE_6_3", 603);
define("LOCATION_AGENT_SPACE_6_4", 604);
define("LOCATION_ORDER_SPACE_1_5", 105);
define("LOCATION_ORDER_SPACE_2_3", 203);
define("LOCATION_ORDER_SPACE_2_5", 205);
define("LOCATION_ORDER_SPACE_2_7", 207);
define("LOCATION_ORDER_SPACE_3_2", 302);
define("LOCATION_ORDER_SPACE_3_4", 304);
define("LOCATION_ORDER_SPACE_3_6", 306);
define("LOCATION_ORDER_SPACE_3_8", 308);
define("LOCATION_ORDER_SPACE_4_3", 403);
define("LOCATION_ORDER_SPACE_4_5", 405);
define("LOCATION_ORDER_SPACE_4_7", 407);
define("LOCATION_ORDER_SPACE_5_2", 502);
define("LOCATION_ORDER_SPACE_5_4", 504);
define("LOCATION_ORDER_SPACE_5_6", 506);
define("LOCATION_ORDER_SPACE_5_8", 508);
define("LOCATION_ORDER_SPACE_6_3", 603);
define("LOCATION_ORDER_SPACE_6_5", 605);
define("LOCATION_ORDER_SPACE_6_7", 607);
define("LOCATION_ORDER_SPACE_7_1", 701);
define("LOCATION_ORDER_SPACE_7_2", 702);
define("LOCATION_ORDER_SPACE_7_4", 704);
define("LOCATION_ORDER_SPACE_7_6", 706);
define("LOCATION_ORDER_SPACE_7_8", 708);
define("LOCATION_ORDER_SPACE_7_9", 709);
define("LOCATION_ORDER_SPACE_8_3", 803);
define("LOCATION_ORDER_SPACE_8_5", 805);
define("LOCATION_ORDER_SPACE_8_7", 807);
define("LOCATION_ORDER_SPACE_9_2", 902);
define("LOCATION_ORDER_SPACE_9_4", 904);
define("LOCATION_ORDER_SPACE_9_6", 906);
define("LOCATION_ORDER_SPACE_9_8", 908);
define("LOCATION_ORDER_SPACE_10_3", 1003);
define("LOCATION_ORDER_SPACE_10_5", 1005);
define("LOCATION_ORDER_SPACE_10_7", 1007);
define("LOCATION_ORDER_SPACE_11_2", 1102);
define("LOCATION_ORDER_SPACE_11_4", 1104);
define("LOCATION_ORDER_SPACE_11_6", 1106);
define("LOCATION_ORDER_SPACE_11_8", 1108);
define("LOCATION_ORDER_SPACE_12_3", 1203);
define("LOCATION_ORDER_SPACE_12_5", 1205);
define("LOCATION_ORDER_SPACE_12_7", 1207);
define("LOCATION_ORDER_SPACE_13_5", 1305);
define("LOCATION_EXTRA_EDITOR", 9999);

/** Player Colors */
define("PLAYER_COLOR_BROWN", "7d4b48");
define("PLAYER_COLOR_SALMON", "ec9b7c");
define("PLAYER_COLOR_TEAL", "5fa7a3");
define("PLAYER_COLOR_YELLOW", "f7c96d");

/** Sales Order Spaces by Player Count */
define("SALES_ORDER_SPACES", [
    2 => [
        LOCATION_ORDER_SPACE_2_5,
        LOCATION_ORDER_SPACE_3_4,
        LOCATION_ORDER_SPACE_3_6,
        LOCATION_ORDER_SPACE_4_3,
        LOCATION_ORDER_SPACE_4_5,
        LOCATION_ORDER_SPACE_4_7,
        LOCATION_ORDER_SPACE_5_2,
        LOCATION_ORDER_SPACE_5_4,
        LOCATION_ORDER_SPACE_5_6,
        LOCATION_ORDER_SPACE_5_8,
        LOCATION_ORDER_SPACE_6_3,
        LOCATION_ORDER_SPACE_6_5,
        LOCATION_ORDER_SPACE_6_7,
        LOCATION_ORDER_SPACE_7_2,
        LOCATION_ORDER_SPACE_7_4,
        LOCATION_ORDER_SPACE_7_6,
        LOCATION_ORDER_SPACE_7_8,
        LOCATION_ORDER_SPACE_8_3,
        LOCATION_ORDER_SPACE_8_5,
        LOCATION_ORDER_SPACE_8_7,
        LOCATION_ORDER_SPACE_9_2,
        LOCATION_ORDER_SPACE_9_4,
        LOCATION_ORDER_SPACE_9_6,
        LOCATION_ORDER_SPACE_9_8,
        LOCATION_ORDER_SPACE_10_3,
        LOCATION_ORDER_SPACE_10_5,
        LOCATION_ORDER_SPACE_10_7,
        LOCATION_ORDER_SPACE_11_4,
        LOCATION_ORDER_SPACE_11_6,
        LOCATION_ORDER_SPACE_12_5,
    ],
    3 => [
        LOCATION_ORDER_SPACE_2_3,
        LOCATION_ORDER_SPACE_2_7,
        LOCATION_ORDER_SPACE_3_2,
        LOCATION_ORDER_SPACE_3_4,
        LOCATION_ORDER_SPACE_3_6,
        LOCATION_ORDER_SPACE_3_8,
        LOCATION_ORDER_SPACE_4_3,
        LOCATION_ORDER_SPACE_4_5,
        LOCATION_ORDER_SPACE_4_7,
        LOCATION_ORDER_SPACE_5_2,
        LOCATION_ORDER_SPACE_5_4,
        LOCATION_ORDER_SPACE_5_6,
        LOCATION_ORDER_SPACE_5_8,
        LOCATION_ORDER_SPACE_6_3,
        LOCATION_ORDER_SPACE_6_5,
        LOCATION_ORDER_SPACE_6_7,
        LOCATION_ORDER_SPACE_7_2,
        LOCATION_ORDER_SPACE_7_4,
        LOCATION_ORDER_SPACE_7_6,
        LOCATION_ORDER_SPACE_7_8,
        LOCATION_ORDER_SPACE_8_3,
        LOCATION_ORDER_SPACE_8_5,
        LOCATION_ORDER_SPACE_8_7,
        LOCATION_ORDER_SPACE_9_2,
        LOCATION_ORDER_SPACE_9_4,
        LOCATION_ORDER_SPACE_9_6,
        LOCATION_ORDER_SPACE_9_8,
        LOCATION_ORDER_SPACE_10_3,
        LOCATION_ORDER_SPACE_10_5,
        LOCATION_ORDER_SPACE_10_7,
        LOCATION_ORDER_SPACE_11_2,
        LOCATION_ORDER_SPACE_11_4,
        LOCATION_ORDER_SPACE_11_6,
        LOCATION_ORDER_SPACE_11_8,
        LOCATION_ORDER_SPACE_12_3,
        LOCATION_ORDER_SPACE_12_7,
    ],
    4 => [
        LOCATION_ORDER_SPACE_1_5,
        LOCATION_ORDER_SPACE_2_3,
        LOCATION_ORDER_SPACE_2_5,
        LOCATION_ORDER_SPACE_2_7,
        LOCATION_ORDER_SPACE_3_2,
        LOCATION_ORDER_SPACE_3_4,
        LOCATION_ORDER_SPACE_3_6,
        LOCATION_ORDER_SPACE_3_8,
        LOCATION_ORDER_SPACE_4_3,
        LOCATION_ORDER_SPACE_4_5,
        LOCATION_ORDER_SPACE_4_7,
        LOCATION_ORDER_SPACE_5_2,
        LOCATION_ORDER_SPACE_5_4,
        LOCATION_ORDER_SPACE_5_6,
        LOCATION_ORDER_SPACE_5_8,
        LOCATION_ORDER_SPACE_6_3,
        LOCATION_ORDER_SPACE_6_5,
        LOCATION_ORDER_SPACE_6_7,
        LOCATION_ORDER_SPACE_7_1,
        LOCATION_ORDER_SPACE_7_2,
        LOCATION_ORDER_SPACE_7_4,
        LOCATION_ORDER_SPACE_7_6,
        LOCATION_ORDER_SPACE_7_8,
        LOCATION_ORDER_SPACE_7_9,
        LOCATION_ORDER_SPACE_8_3,
        LOCATION_ORDER_SPACE_8_5,
        LOCATION_ORDER_SPACE_8_7,
        LOCATION_ORDER_SPACE_9_2,
        LOCATION_ORDER_SPACE_9_4,
        LOCATION_ORDER_SPACE_9_6,
        LOCATION_ORDER_SPACE_9_8,
        LOCATION_ORDER_SPACE_10_3,
        LOCATION_ORDER_SPACE_10_5,
        LOCATION_ORDER_SPACE_10_7,
        LOCATION_ORDER_SPACE_11_2,
        LOCATION_ORDER_SPACE_11_4,
        LOCATION_ORDER_SPACE_11_6,
        LOCATION_ORDER_SPACE_11_8,
        LOCATION_ORDER_SPACE_12_3,
        LOCATION_ORDER_SPACE_12_5,
        LOCATION_ORDER_SPACE_12_7,
        LOCATION_ORDER_SPACE_13_5,
    ],
]);

/** Sales Order Connections by Player Count */
define("SALES_ORDER_CONNECTIONS", [
    2 => [
        LOCATION_AGENT_SPACE_1_2 => [
            LOCATION_ORDER_SPACE_2_5,
            LOCATION_ORDER_SPACE_3_4,
        ],
        LOCATION_AGENT_SPACE_1_3 => [
            LOCATION_ORDER_SPACE_2_5,
            LOCATION_ORDER_SPACE_3_6,
        ],
        LOCATION_AGENT_SPACE_2_1 => [
            LOCATION_ORDER_SPACE_4_3,
            LOCATION_ORDER_SPACE_5_2,
        ],
        LOCATION_AGENT_SPACE_2_2 => [
            LOCATION_ORDER_SPACE_3_4,
            LOCATION_ORDER_SPACE_4_3,
            LOCATION_ORDER_SPACE_4_5,
            LOCATION_ORDER_SPACE_5_4,
        ],
        LOCATION_AGENT_SPACE_2_3 => [
            LOCATION_ORDER_SPACE_3_6,
            LOCATION_ORDER_SPACE_4_5,
            LOCATION_ORDER_SPACE_4_7,
            LOCATION_ORDER_SPACE_5_6,
        ],
        LOCATION_AGENT_SPACE_2_4 => [
            LOCATION_ORDER_SPACE_4_7,
            LOCATION_ORDER_SPACE_5_8,
        ],
        LOCATION_AGENT_SPACE_3_1 => [
            LOCATION_ORDER_SPACE_5_2,
            LOCATION_ORDER_SPACE_6_3,
            LOCATION_ORDER_SPACE_7_2,
        ],
        LOCATION_AGENT_SPACE_3_2 => [
            LOCATION_ORDER_SPACE_5_4,
            LOCATION_ORDER_SPACE_6_3,
            LOCATION_ORDER_SPACE_6_5,
            LOCATION_ORDER_SPACE_7_4,
        ],
        LOCATION_AGENT_SPACE_3_3 => [
            LOCATION_ORDER_SPACE_5_6,
            LOCATION_ORDER_SPACE_6_5,
            LOCATION_ORDER_SPACE_6_7,
            LOCATION_ORDER_SPACE_7_6,
        ],
        LOCATION_AGENT_SPACE_3_4 => [
            LOCATION_ORDER_SPACE_5_8,
            LOCATION_ORDER_SPACE_6_7,
            LOCATION_ORDER_SPACE_7_8,
        ],
        LOCATION_AGENT_SPACE_4_1 => [
            LOCATION_ORDER_SPACE_7_2,
            LOCATION_ORDER_SPACE_8_3,
            LOCATION_ORDER_SPACE_9_2,
        ],
        LOCATION_AGENT_SPACE_4_2 => [
            LOCATION_ORDER_SPACE_7_4,
            LOCATION_ORDER_SPACE_8_3,
            LOCATION_ORDER_SPACE_8_5,
            LOCATION_ORDER_SPACE_9_4,
        ],
        LOCATION_AGENT_SPACE_4_3 => [
            LOCATION_ORDER_SPACE_7_6,
            LOCATION_ORDER_SPACE_8_5,
            LOCATION_ORDER_SPACE_8_7,
            LOCATION_ORDER_SPACE_9_6,
        ],
        LOCATION_AGENT_SPACE_4_4 => [
            LOCATION_ORDER_SPACE_7_8,
            LOCATION_ORDER_SPACE_8_7,
            LOCATION_ORDER_SPACE_9_8,
        ],
        LOCATION_AGENT_SPACE_5_1 => [
            LOCATION_ORDER_SPACE_9_2,
            LOCATION_ORDER_SPACE_10_3,
        ],
        LOCATION_AGENT_SPACE_5_2 => [
            LOCATION_ORDER_SPACE_9_4,
            LOCATION_ORDER_SPACE_10_3,
            LOCATION_ORDER_SPACE_10_5,
            LOCATION_ORDER_SPACE_11_4,
        ],
        LOCATION_AGENT_SPACE_5_3 => [
            LOCATION_ORDER_SPACE_9_6,
            LOCATION_ORDER_SPACE_10_5,
            LOCATION_ORDER_SPACE_10_7,
            LOCATION_ORDER_SPACE_11_6,
        ],
        LOCATION_AGENT_SPACE_5_4 => [
            LOCATION_ORDER_SPACE_9_8,
            LOCATION_ORDER_SPACE_10_7,
        ],
        LOCATION_AGENT_SPACE_6_2 => [
            LOCATION_ORDER_SPACE_11_4,
            LOCATION_ORDER_SPACE_12_5,
        ],
        LOCATION_AGENT_SPACE_6_3 => [
            LOCATION_ORDER_SPACE_11_6,
            LOCATION_ORDER_SPACE_12_5,
        ],
    ],
    3 => [
        LOCATION_AGENT_SPACE_1_1 => [
            LOCATION_ORDER_SPACE_2_3,
            LOCATION_ORDER_SPACE_3_2,
        ],
        LOCATION_AGENT_SPACE_1_2 => [
            LOCATION_ORDER_SPACE_2_3,
            LOCATION_ORDER_SPACE_3_4,
        ],
        LOCATION_AGENT_SPACE_1_3 => [
            LOCATION_ORDER_SPACE_2_7,
            LOCATION_ORDER_SPACE_3_6,
        ],
        LOCATION_AGENT_SPACE_1_4 => [
            LOCATION_ORDER_SPACE_2_7,
            LOCATION_ORDER_SPACE_3_8,
        ],
        LOCATION_AGENT_SPACE_2_1 => [
            LOCATION_ORDER_SPACE_3_2,
            LOCATION_ORDER_SPACE_4_3,
            LOCATION_ORDER_SPACE_5_2,
        ],
        LOCATION_AGENT_SPACE_2_2 => [
            LOCATION_ORDER_SPACE_3_4,
            LOCATION_ORDER_SPACE_4_3,
            LOCATION_ORDER_SPACE_4_5,
            LOCATION_ORDER_SPACE_5_4,
        ],
        LOCATION_AGENT_SPACE_2_3 => [
            LOCATION_ORDER_SPACE_3_6,
            LOCATION_ORDER_SPACE_4_5,
            LOCATION_ORDER_SPACE_4_7,
            LOCATION_ORDER_SPACE_5_6,
        ],
        LOCATION_AGENT_SPACE_2_4 => [
            LOCATION_ORDER_SPACE_3_8,
            LOCATION_ORDER_SPACE_4_7,
            LOCATION_ORDER_SPACE_5_8,
        ],
        LOCATION_AGENT_SPACE_3_1 => [
            LOCATION_ORDER_SPACE_5_2,
            LOCATION_ORDER_SPACE_6_3,
            LOCATION_ORDER_SPACE_7_2,
        ],
        LOCATION_AGENT_SPACE_3_2 => [
            LOCATION_ORDER_SPACE_5_4,
            LOCATION_ORDER_SPACE_6_3,
            LOCATION_ORDER_SPACE_6_5,
            LOCATION_ORDER_SPACE_7_4,
        ],
        LOCATION_AGENT_SPACE_3_3 => [
            LOCATION_ORDER_SPACE_5_6,
            LOCATION_ORDER_SPACE_6_5,
            LOCATION_ORDER_SPACE_6_7,
            LOCATION_ORDER_SPACE_7_6,
        ],
        LOCATION_AGENT_SPACE_3_4 => [
            LOCATION_ORDER_SPACE_5_8,
            LOCATION_ORDER_SPACE_6_7,
            LOCATION_ORDER_SPACE_7_8,
        ],
        LOCATION_AGENT_SPACE_4_1 => [
            LOCATION_ORDER_SPACE_7_2,
            LOCATION_ORDER_SPACE_8_3,
            LOCATION_ORDER_SPACE_9_2,
        ],
        LOCATION_AGENT_SPACE_4_2 => [
            LOCATION_ORDER_SPACE_7_4,
            LOCATION_ORDER_SPACE_8_3,
            LOCATION_ORDER_SPACE_8_5,
            LOCATION_ORDER_SPACE_9_4,
        ],
        LOCATION_AGENT_SPACE_4_3 => [
            LOCATION_ORDER_SPACE_7_6,
            LOCATION_ORDER_SPACE_8_5,
            LOCATION_ORDER_SPACE_8_7,
            LOCATION_ORDER_SPACE_9_6,
        ],
        LOCATION_AGENT_SPACE_4_4 => [
            LOCATION_ORDER_SPACE_7_8,
            LOCATION_ORDER_SPACE_8_7,
            LOCATION_ORDER_SPACE_9_8,
        ],
        LOCATION_AGENT_SPACE_5_1 => [
            LOCATION_ORDER_SPACE_9_2,
            LOCATION_ORDER_SPACE_10_3,
            LOCATION_ORDER_SPACE_11_2,
        ],
        LOCATION_AGENT_SPACE_5_2 => [
            LOCATION_ORDER_SPACE_9_4,
            LOCATION_ORDER_SPACE_10_3,
            LOCATION_ORDER_SPACE_10_5,
            LOCATION_ORDER_SPACE_11_4,
        ],
        LOCATION_AGENT_SPACE_5_3 => [
            LOCATION_ORDER_SPACE_9_6,
            LOCATION_ORDER_SPACE_10_5,
            LOCATION_ORDER_SPACE_10_7,
            LOCATION_ORDER_SPACE_11_6,
        ],
        LOCATION_AGENT_SPACE_5_4 => [
            LOCATION_ORDER_SPACE_9_8,
            LOCATION_ORDER_SPACE_10_7,
            LOCATION_ORDER_SPACE_11_8,
        ],
        LOCATION_AGENT_SPACE_6_1 => [
            LOCATION_ORDER_SPACE_11_2,
            LOCATION_ORDER_SPACE_12_3,
        ],
        LOCATION_AGENT_SPACE_6_2 => [
            LOCATION_ORDER_SPACE_11_4,
            LOCATION_ORDER_SPACE_12_3,
        ],
        LOCATION_AGENT_SPACE_6_3 => [
            LOCATION_ORDER_SPACE_11_6,
            LOCATION_ORDER_SPACE_12_7,
        ],
        LOCATION_AGENT_SPACE_6_4 => [
            LOCATION_ORDER_SPACE_11_8,
            LOCATION_ORDER_SPACE_12_7,
        ],
    ],
    4 => [
        LOCATION_AGENT_SPACE_1_1 => [
            LOCATION_ORDER_SPACE_2_3,
            LOCATION_ORDER_SPACE_3_2,
        ],
        LOCATION_AGENT_SPACE_1_2 => [
            LOCATION_ORDER_SPACE_1_5,
            LOCATION_ORDER_SPACE_2_3,
            LOCATION_ORDER_SPACE_2_5,
            LOCATION_ORDER_SPACE_3_4,
        ],
        LOCATION_AGENT_SPACE_1_3 => [
            LOCATION_ORDER_SPACE_1_5,
            LOCATION_ORDER_SPACE_2_5,
            LOCATION_ORDER_SPACE_2_7,
            LOCATION_ORDER_SPACE_3_6,
        ],
        LOCATION_AGENT_SPACE_1_4 => [
            LOCATION_ORDER_SPACE_2_7,
            LOCATION_ORDER_SPACE_3_8,
        ],
        LOCATION_AGENT_SPACE_2_1 => [
            LOCATION_ORDER_SPACE_3_2,
            LOCATION_ORDER_SPACE_4_3,
            LOCATION_ORDER_SPACE_5_2,
        ],
        LOCATION_AGENT_SPACE_2_2 => [
            LOCATION_ORDER_SPACE_3_4,
            LOCATION_ORDER_SPACE_4_3,
            LOCATION_ORDER_SPACE_4_5,
            LOCATION_ORDER_SPACE_5_4,
        ],
        LOCATION_AGENT_SPACE_2_3 => [
            LOCATION_ORDER_SPACE_3_6,
            LOCATION_ORDER_SPACE_4_5,
            LOCATION_ORDER_SPACE_4_7,
            LOCATION_ORDER_SPACE_5_6,
        ],
        LOCATION_AGENT_SPACE_2_4 => [
            LOCATION_ORDER_SPACE_3_8,
            LOCATION_ORDER_SPACE_4_7,
            LOCATION_ORDER_SPACE_5_8,
        ],
        LOCATION_AGENT_SPACE_3_1 => [
            LOCATION_ORDER_SPACE_5_2,
            LOCATION_ORDER_SPACE_6_3,
            LOCATION_ORDER_SPACE_7_1,
            LOCATION_ORDER_SPACE_7_2,
        ],
        LOCATION_AGENT_SPACE_3_2 => [
            LOCATION_ORDER_SPACE_5_4,
            LOCATION_ORDER_SPACE_6_3,
            LOCATION_ORDER_SPACE_6_5,
            LOCATION_ORDER_SPACE_7_4,
        ],
        LOCATION_AGENT_SPACE_3_3 => [
            LOCATION_ORDER_SPACE_5_6,
            LOCATION_ORDER_SPACE_6_5,
            LOCATION_ORDER_SPACE_6_7,
            LOCATION_ORDER_SPACE_7_6,
        ],
        LOCATION_AGENT_SPACE_3_4 => [
            LOCATION_ORDER_SPACE_5_8,
            LOCATION_ORDER_SPACE_6_7,
            LOCATION_ORDER_SPACE_7_8,
            LOCATION_ORDER_SPACE_7_9,
        ],
        LOCATION_AGENT_SPACE_4_1 => [
            LOCATION_ORDER_SPACE_7_1,
            LOCATION_ORDER_SPACE_7_2,
            LOCATION_ORDER_SPACE_8_3,
            LOCATION_ORDER_SPACE_9_2,
        ],
        LOCATION_AGENT_SPACE_4_2 => [
            LOCATION_ORDER_SPACE_7_4,
            LOCATION_ORDER_SPACE_8_3,
            LOCATION_ORDER_SPACE_8_5,
            LOCATION_ORDER_SPACE_9_4,
        ],
        LOCATION_AGENT_SPACE_4_3 => [
            LOCATION_ORDER_SPACE_7_6,
            LOCATION_ORDER_SPACE_8_5,
            LOCATION_ORDER_SPACE_8_7,
            LOCATION_ORDER_SPACE_9_6,
        ],
        LOCATION_AGENT_SPACE_4_4 => [
            LOCATION_ORDER_SPACE_7_8,
            LOCATION_ORDER_SPACE_7_9,
            LOCATION_ORDER_SPACE_8_7,
            LOCATION_ORDER_SPACE_9_8,
        ],
        LOCATION_AGENT_SPACE_5_1 => [
            LOCATION_ORDER_SPACE_9_2,
            LOCATION_ORDER_SPACE_10_3,
            LOCATION_ORDER_SPACE_11_2,
        ],
        LOCATION_AGENT_SPACE_5_2 => [
            LOCATION_ORDER_SPACE_9_4,
            LOCATION_ORDER_SPACE_10_3,
            LOCATION_ORDER_SPACE_10_5,
            LOCATION_ORDER_SPACE_11_4,
        ],
        LOCATION_AGENT_SPACE_5_3 => [
            LOCATION_ORDER_SPACE_9_6,
            LOCATION_ORDER_SPACE_10_5,
            LOCATION_ORDER_SPACE_10_7,
            LOCATION_ORDER_SPACE_11_6,
        ],
        LOCATION_AGENT_SPACE_5_4 => [
            LOCATION_ORDER_SPACE_9_8,
            LOCATION_ORDER_SPACE_10_7,
            LOCATION_ORDER_SPACE_11_8,
        ],
        LOCATION_AGENT_SPACE_6_1 => [
            LOCATION_ORDER_SPACE_11_2,
            LOCATION_ORDER_SPACE_12_3,
        ],
        LOCATION_AGENT_SPACE_6_2 => [
            LOCATION_ORDER_SPACE_11_4,
            LOCATION_ORDER_SPACE_12_3,
            LOCATION_ORDER_SPACE_12_5,
            LOCATION_ORDER_SPACE_13_5,
        ],
        LOCATION_AGENT_SPACE_6_3 => [
            LOCATION_ORDER_SPACE_11_6,
            LOCATION_ORDER_SPACE_12_5,
            LOCATION_ORDER_SPACE_12_7,
            LOCATION_ORDER_SPACE_13_5,
        ],
        LOCATION_AGENT_SPACE_6_4 => [
            LOCATION_ORDER_SPACE_11_8,
            LOCATION_ORDER_SPACE_12_7,
        ],
    ],
]);

/** State Args */
define("STATE_ARG_GAME_END", "argGameEnd");

/** State IDs */
define("ST_GAME_SETUP", 1);
define("ST_PLAYER_SETUP", 2);
define("ST_GAME_END", 99);

/** State Names */
define("GAME_END", "gameEnd");
define("GAME_SETUP", "gameSetup");
define("PLAYER_SETUP", "playerSetup");

/** State Types */
define("STATE_TYPE_ACTIVE_PLAYER", "activeplayer");
define("STATE_TYPE_GAME", "game");
define("STATE_TYPE_MANAGER", "manager");
define("STATE_TYPE_MULTIPLE_ACTIVE_PLAYER", "multipleactiveplayer");
