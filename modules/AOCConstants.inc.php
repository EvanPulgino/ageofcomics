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
define("GAME_ACTION_COMPLETE_SETUP", "stCompleteSetup");
define("GAME_ACTION_GAME_END", "stGameEnd");
define("GAME_ACTION_GAME_SETUP", "stGameSetup");
define("GAME_ACTION_PLAYER_SETUP", "stPlayerSetup");
define("GAME_ACTION_NEXT_PLAYER_SETUP", "stNextPlayerSetup");
define("GAME_ACTION_START_NEW_ROUND", "stStartNewRound");

define("PLAYER_ACTION_SELECT_START_ITEMS", "selectStartItems");

/** State Args */
define("ARGS_PLAYER_SETUP", "argsPlayerSetup");

/** Genre Keys */
define("GENRE_CRIME", 10);
define("GENRE_HORROR", 20);
define("GENRE_ROMANCE", 30);
define("GENRE_SCIFI", 40);
define("GENRE_SUPERHERO", 50);
define("GENRE_WESTERN", 60);
define("GENRE_KEYS", [
    GENRE_CRIME,
    GENRE_HORROR,
    GENRE_ROMANCE,
    GENRE_SCIFI,
    GENRE_SUPERHERO,
    GENRE_WESTERN,
]);

/** Genre Names */
define("CRIME", "crime");
define("HORROR", "horror");
define("ROMANCE", "romance");
define("SCIFI", "scifi");
define("SUPERHERO", "superhero");
define("WESTERN", "western");

/** Genres */
define("GENRES", [
    GENRE_CRIME => CRIME,
    GENRE_HORROR => HORROR,
    GENRE_ROMANCE => ROMANCE,
    GENRE_SCIFI => SCIFI,
    GENRE_SUPERHERO => SUPERHERO,
    GENRE_WESTERN => WESTERN,
]);

/** Card Types */
define("CARD_TYPE_ARTIST", 1);
define("CARD_TYPE_WRITER", 2);
define("CARD_TYPE_COMIC", 3);
define("CARD_TYPE_RIPOFF", 4);

/** Card Type Names */
define("ARTIST", "artist");
define("COMIC", "comic");
define("RIPOFF", "ripoff");
define("WRITER", "writer");

/** Comic Bonuses */
define("PLUS_ONE_FAN", 1);
define("TWO_IDEAS", 2);
define("SUPER_TRANSPORT", 3);
define("PLUS_FOUR_MONEY", 4);

/** Comic Names */
define("ALIEN_WORLDS", "ALIEN WORLDS");
define("ANGEL_OF_LIBERTY", "ANGEL of LIBERTY");
define("CALL_THE_POLICE", "CALL the POLICE");
define("CARMILLA", "CARMiLLA");
define("FREEDOM_COMICS", "FREEDOM Comics");
define("FUTURE_WONDER", "FUTURE Wonder");
define("GANG_WARS", "GANG WARS!");
define("HAUNTING_TALES", "HAUNTING TALES");
define("HEARTBREAKERS", "Heartbreakers!");
define("HEY_RANGER", "HEY RANGER");
define("IT_LIVES", "IT LIVES");
define("ITS_A_FELONY", "IT'S A FELONY");
define("JUST_A_FEELING", "JUST A Feeling");
define("KILLER_DAMES", "KiLLER Dames");
define("KINGS_OF_THE_PLAINS", "KINGS OF THE PLAINS");
define("LOVE_LETTER", "Love Letter");
define("MISS_TIGER", "Miss Tiger");
define("NEPTUNIO", "NEPTUNIO");
define("OUTLAWS", "OUTLAWS");
define("STAR_SPANGLED_DUO", "STAR-SPANGLED DUO!");
define("STORIES_OF_TOMORROW", "STORIES OF TOMORROW");
define("TEEN_DRAMA", "TEEN DRAMA");
define("TRUE_TERROR", "TRUE TERROR");
define("WILD_ANNIE", "WILD ANNIE");

/** Ripoff Names */
define("WEIRD_WORLDS", "WEIRD WORLDS");
define("WINGS_OF_LIBERTY", "WINGS of LIBERTY");
define("CALL_THE_COPS", "CALL the COPSs");
define("VAMPYRIA", "VAMPYRiA");
define("JUSTICE_COMICS", "JUSTICE Comics");
define("FUTURE_MARVELS", "FUTURE Marvels");
define("BANG_WARS", "BANG WARS!");
define("HARROWING_TALES", "HARROWING TALES");
define("TEARJERKERS", "Tearjerkers!");
define("HI_RANGER", "HI RANGER");
define("IT_WALKS", "IT WALKS");
define("ITS_A_LARCENY", "IT'S A LARCENY");
define("JUST_AN_EMOTION", "JUST AN Emotion");
define("DEADLY_DAMES", "DEADLY Dames");
define("KINGS_OF_THE_PEAKS", "KINGS OF THE PEAKS");
define("LOVE_NOTES", "Love Notes");
define("MISS_KITTY", "Miss Kitty");
define("SATURNIO", "SATURNIO");
define("BANDITS", "BANDITS");
define("STAR_SPANGLED_DUDS", "STAR-SPANGLED DUDS!");
define("LEGENDS_OF_TOMORROW", "LEGENDS OF TOMORROW");
define("TWIN_DRAMA", "TWIN DRAMA");
define("TRUE_HORROR", "TRUE HORROR");
define("WILD_JENNY", "WILD JENNY");

/** Comic Card Breakdown */
define("COMIC_CARDS", [
    GENRE_CRIME => [
        PLUS_ONE_FAN => KILLER_DAMES,
        TWO_IDEAS => ITS_A_FELONY,
        SUPER_TRANSPORT => CALL_THE_POLICE,
        PLUS_FOUR_MONEY => GANG_WARS,
    ],
    GENRE_HORROR => [
        PLUS_ONE_FAN => HAUNTING_TALES,
        TWO_IDEAS => IT_LIVES,
        SUPER_TRANSPORT => TRUE_TERROR,
        PLUS_FOUR_MONEY => CARMILLA,
    ],

    GENRE_ROMANCE => [
        PLUS_ONE_FAN => JUST_A_FEELING,
        TWO_IDEAS => LOVE_LETTER,
        SUPER_TRANSPORT => HEARTBREAKERS,
        PLUS_FOUR_MONEY => TEEN_DRAMA,
    ],

    GENRE_SCIFI => [
        PLUS_ONE_FAN => STORIES_OF_TOMORROW,
        TWO_IDEAS => NEPTUNIO,
        SUPER_TRANSPORT => FUTURE_WONDER,
        PLUS_FOUR_MONEY => ALIEN_WORLDS,
    ],
    GENRE_SUPERHERO => [
        PLUS_ONE_FAN => STAR_SPANGLED_DUO,
        TWO_IDEAS => MISS_TIGER,
        SUPER_TRANSPORT => ANGEL_OF_LIBERTY,
        PLUS_FOUR_MONEY => FREEDOM_COMICS,
    ],
    GENRE_WESTERN => [
        PLUS_ONE_FAN => KINGS_OF_THE_PLAINS,
        TWO_IDEAS => HEY_RANGER,
        SUPER_TRANSPORT => OUTLAWS,
        PLUS_FOUR_MONEY => WILD_ANNIE,
    ],
]);

define("RIPOFF_CARDS", [
    GENRE_CRIME => [
        PLUS_ONE_FAN => DEADLY_DAMES,
        TWO_IDEAS => ITS_A_LARCENY,
        SUPER_TRANSPORT => CALL_THE_COPS,
        PLUS_FOUR_MONEY => BANG_WARS,
    ],
    GENRE_HORROR => [
        PLUS_ONE_FAN => HARROWING_TALES,
        TWO_IDEAS => IT_WALKS,
        SUPER_TRANSPORT => TRUE_HORROR,
        PLUS_FOUR_MONEY => VAMPYRIA,
    ],
    GENRE_ROMANCE => [
        PLUS_ONE_FAN => JUST_AN_EMOTION,
        TWO_IDEAS => LOVE_NOTES,
        SUPER_TRANSPORT => TEARJERKERS,
        PLUS_FOUR_MONEY => TWIN_DRAMA,
    ],
    GENRE_SCIFI => [
        PLUS_ONE_FAN => LEGENDS_OF_TOMORROW,
        TWO_IDEAS => SATURNIO,
        SUPER_TRANSPORT => FUTURE_MARVELS,
        PLUS_FOUR_MONEY => WEIRD_WORLDS,
    ],
    GENRE_SUPERHERO => [
        PLUS_ONE_FAN => STAR_SPANGLED_DUDS,
        TWO_IDEAS => MISS_KITTY,
        SUPER_TRANSPORT => WINGS_OF_LIBERTY,
        PLUS_FOUR_MONEY => JUSTICE_COMICS,
    ],
    GENRE_WESTERN => [
        PLUS_ONE_FAN => KINGS_OF_THE_PEAKS,
        TWO_IDEAS => HI_RANGER,
        SUPER_TRANSPORT => BANDITS,
        PLUS_FOUR_MONEY => WILD_JENNY,
    ],
]);

/** Global Variables */
define("CARD_SUPPLY_SIZE", "card_supply_size");
define("CURRENT_ROUND", "current_round");
define("IDEAS_SPACE_CRIME", "ideas_space_crime");
define("IDEAS_SPACE_HORROR", "ideas_space_horror");
define("IDEAS_SPACE_ROMANCE", "ideas_space_romance");
define("IDEAS_SPACE_SCIFI", "ideas_space_scifi");
define("IDEAS_SPACE_SUPERHERO", "ideas_space_superhero");
define("IDEAS_SPACE_WESTERN", "ideas_space_western");
define("TICKET_SUPPLY", "ticket_supply");
define("TOTAL_TURNS", "total_turns");
define("TURNS_TAKEN", "turns_taken");
define("START_IDEAS", "start_ideas");

/** Locations */
define("LOCATION_VOID", -1);
define("LOCATION_DECK", 0);
define("LOCATION_DISCARD", 1);
define("LOCATION_HAND", 2);
define("LOCATION_PLAYER_AREA", 3);
define("LOCATION_SUPPLY", 4);
define("LOCATION_PLAYER_MAT", 5);
define("LOCATION_CHART", 6);
define("LOCATION_HYPE", 7);
define("LOCATION_EXTRA_EDITOR", 8);
define("LOCATION_MAP", 9);
define("LOCATION_ACTION_SPACE_1", 1);
define("LOCATION_ACTION_SPACE_2", 2);
define("LOCATION_ACTION_SPACE_3", 3);
define("LOCATION_ACTION_SPACE_4", 4);
define("LOCATION_ACTION_SPACE_5", 5);
define("LOCATION_ACTION_HIRE", 10000);
define("LOCATION_ACTION_DEVELOP", 20000);
define("LOCATION_ACTION_IDEAS", 30000);
define("LOCATION_ACTION_PRINT", 40000);
define("LOCATION_ACTION_ROYALTIES", 50000);
define("LOCATION_ACTION_SALES", 60000);
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

/** Player Colors */
define("PLAYER_COLOR_BROWN", "8e514e");
define("PLAYER_COLOR_SALMON", "e5977a");
define("PLAYER_COLOR_TEAL", "5ba59f");
define("PLAYER_COLOR_YELLOW", "f5c86e");

/** Player Colors as String */
define("PLAYER_COLORS", [
    PLAYER_COLOR_BROWN => "brown",
    PLAYER_COLOR_SALMON => "salmon",
    PLAYER_COLOR_TEAL => "teal",
    PLAYER_COLOR_YELLOW => "yellow",
]);

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
define("ST_NEXT_PLAYER_SETUP", 3);
define("ST_COMPLETE_SETUP", 4);
define("ST_START_NEW_ROUND", 10);
define("ST_GAME_END", 99);

/** State Names */
define("COMPLETE_SETUP", "completeSetup");
define("GAME_END", "gameEnd");
define("GAME_SETUP", "gameSetup");
define("NEXT_PLAYER_SETUP", "nextPlayerSetup");
define("PLAYER_SETUP", "playerSetup");
define("START_NEW_ROUND", "startNewRound");

/** State Types */
define("STATE_TYPE_ACTIVE_PLAYER", "activeplayer");
define("STATE_TYPE_GAME", "game");
define("STATE_TYPE_MANAGER", "manager");
define("STATE_TYPE_MULTIPLE_ACTIVE_PLAYER", "multipleactiveplayer");
