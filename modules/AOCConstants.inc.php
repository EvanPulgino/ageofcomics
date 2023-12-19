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
 * @EvanPulgino
 */

/** Game State Actions */
define("GAME_ACTION_COMPLETE_SETUP", "stCompleteSetup");
define("GAME_ACTION_GAIN_ROYALITES", "stGainRoyalties");
define("GAME_ACTION_GAME_END", "stGameEnd");
define("GAME_ACTION_GAME_SETUP", "stGameSetup");
define("GAME_ACTION_PERFORM_PRINT_BONUS", "stPerformPrintBonus");
define("GAME_ACTION_PLAYER_SETUP", "stPlayerSetup");
define("GAME_ACTION_NEXT_PLAYER", "stNextPlayer");
define("GAME_ACTION_NEXT_PLAYER_SETUP", "stNextPlayerSetup");
define("GAME_ACTION_START_NEW_ROUND", "stStartNewRound");

/** Player Actions */
define("PLAYER_ACTION_COLLECT_SALES_ORDER", "collectSalesOrder");
define("PLAYER_ACTION_DEVELOP_COMIC", "developComic");
define("PLAYER_ACTION_DEVELOP_FROM_GENRE", "developFromGenre");
define("PLAYER_ACTION_FLIP_SALES_ORDER", "flipSalesOrder");
define("PLAYER_ACTION_CONFIRM_GAIN_IDEAS", "confirmGainIdeas");
define("PLAYER_ACTION_CONFIRM_GAIN_BONUS_IDEAS", "confirmGainBonusIdeas");
define("PLAYER_ACTION_HIRE_CREATIVE", "hireCreative");
define("PLAYER_ACTION_MOVE_SALES_AGENT", "moveSalesAgent");
define(
    "PLAYER_ACTION_MOVE_SALES_AGENT_WITH_TICKET",
    "moveSalesAgentWithTicket"
);
define("PLAYER_ACTION_PRINT_COMIC", "printComic");
define("PLAYER_ACTION_PRINT_RIPOFF", "printRipoff");
define("PLAYER_ACTION_SELECT_ACTION_SPACE", "selectActionSpace");
define("PLAYER_ACTION_SELECT_START_ITEMS", "selectStartItems");
define("PLAYER_ACTION_CONFIRM_DISCARD", "confirmDiscard");

/** Generic */
define("CARD_LOCATION_ARG", "card_location_arg");
define("CARD_LOCATION_ARG_DESC", "card_location_arg DESC");

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

/** Genre Key From Name */
define("GENRE_KEY_FROM_NAME", [
    CRIME => GENRE_CRIME,
    HORROR => GENRE_HORROR,
    ROMANCE => GENRE_ROMANCE,
    SCIFI => GENRE_SCIFI,
    SUPERHERO => GENRE_SUPERHERO,
    WESTERN => GENRE_WESTERN,
]);

/** Genre Colors */
define("GENRE_COLORS", [
    GENRE_CRIME => "#8f8b8b",
    GENRE_HORROR => "#7f4586",
    GENRE_ROMANCE => "#e24545",
    GENRE_SCIFI => "#90be5b",
    GENRE_SUPERHERO => "#3b7ab4",
    GENRE_WESTERN => "#e17629",
]);

/** Card Types */
define("CARD_TYPE_ARTIST", 3);
define("CARD_TYPE_WRITER", 2);
define("CARD_TYPE_COMIC", 1);
define("CARD_TYPE_RIPOFF", 4);

/** Card Type Names */
define("ARTIST", "artist");
define("COMIC", "comic");
define("RIPOFF", "ripoff");
define("WRITER", "writer");

/** Card Type Map */
define("CARD_TYPES", [
    CARD_TYPE_ARTIST => ARTIST,
    CARD_TYPE_COMIC => COMIC,
    CARD_TYPE_RIPOFF => RIPOFF,
    CARD_TYPE_WRITER => WRITER,
]);

/** Chart Income Levels */
define("CHART_INCOME_LEVELS", [
    0 => 0,
    1 => 1,
    2 => 2,
    3 => 2,
    4 => 3,
    5 => 3,
    6 => 3,
    7 => 4,
    8 => 4,
    9 => 4,
    10 => 6,
]);

/** Comic Bonuses */
define("PLUS_ONE_FAN", 1);
define("TWO_IDEAS", 2);
define("SUPER_TRANSPORT", 3);
define("PLUS_FOUR_MONEY", 4);

/** Comic Names */
define("ALIEN_WORLDS", "Alien Worlds");
define("ANGEL_OF_LIBERTY", "Angel of Liberty");
define("CALL_THE_POLICE", "Call the Police");
define("CARMILLA", "Carmilla");
define("FREEDOM_COMICS", "Freedom Comics");
define("FUTURE_WONDER", "Future Wonder");
define("GANG_WARS", "Gang Wars");
define("HAUNTING_TALES", "Haunting Tales");
define("HEARTBREAKERS", "Heartbreakers");
define("HEY_RANGER", "Hey Ranger");
define("IT_LIVES", "It Lives");
define("ITS_A_FELONY", "It's a Felony");
define("JUST_A_FEELING", "Just a Feeling");
define("KILLER_DAMES", "Killer Dames");
define("KINGS_OF_THE_PLAINS", "King of the Plains");
define("LOVE_LETTER", "Love Letter");
define("MISS_TIGER", "Miss Tiger");
define("NEPTUNIO", "Neptunio");
define("OUTLAWS", "Outlaws");
define("STAR_SPANGLED_DUO", "Star-Spangled Duo");
define("STORIES_OF_TOMORROW", "Stories of Tomorrow");
define("TEEN_DRAMA", "Teen Drama");
define("TRUE_TERROR", "True Terror");
define("WILD_ANNIE", "Wild Annie");

/** Ripoff Names */
define("BANDITS", "Bandits");
define("BANG_WARS", "Bang Wars");
define("CALL_THE_COPS", "Call the Cops");
define("DEADLY_DAMES", "Deadly Dames");
define("FUTURE_MARVELS", "Future Marvels");
define("HARROWING_TALES", "Harrowing Tales");
define("HI_RANGER", "Hi Ranger");
define("IT_WALKS", "It Walks");
define("ITS_A_LARCENY", "It's a Larceny");
define("JUST_AN_EMOTION", "Just an Emotion");
define("JUSTICE_COMICS", "Justice Comics");
define("KINGS_OF_THE_PEAKS", "Kings of the Peaks");
define("LEGENDS_OF_TOMORROW", "Legends of Tomorrow");
define("LOVE_NOTES", "Love Notes");
define("MISS_KITTY", "Miss Kitty");
define("SATURNIO", "Saturnio");
define("STAR_SPANGLED_DUDS", "Star-Spangled Duds");
define("TEARJERKERS", "Tearjerkers");
define("TWIN_DRAMA", "Twin Drama");
define("TRUE_HORROR", "True Horror");
define("VAMPYRIA", "Vampyria");
define("WEIRD_WORLDS", "Weird Worlds");
define("WILD_JENNY", "Wild Jenny");
define("WINGS_OF_LIBERTY", "Wings of Liberty");

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

/** Ripoff Card Breakdown */
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

/** Combined Comic Breakdown */
define("COMBINED_COMIC_CARDS", [
    CARD_TYPE_COMIC => COMIC_CARDS,
    CARD_TYPE_RIPOFF => RIPOFF_CARDS,
]);

/** Action Keys */
define("HIRE_ACTION", 1);
define("DEVELOP_ACTION", 2);
define("IDEAS_ACTION", 3);
define("PRINT_ACTION", 4);
define("ROYALTIES_ACTION", 5);
define("SALES_ACTION", 6);

/** Action Names */
define("ACTION_STRING_FROM_KEY", [
    HIRE_ACTION => "hire",
    DEVELOP_ACTION => "develop",
    IDEAS_ACTION => "ideas",
    PRINT_ACTION => "print",
    ROYALTIES_ACTION => "royalties",
    SALES_ACTION => "sales",
]);

/** Global Variables */
define("CAN_HIRE_ARTIST", "can_hire_artist");
define("CAN_HIRE_WRITER", "can_hire_writer");
define("CARD_SUPPLY_SIZE", "card_supply_size");
define("CURRENT_ROUND", "current_round");
define("IDEAS_SPACE_CRIME", "ideas_space_crime");
define("IDEAS_SPACE_HORROR", "ideas_space_horror");
define("IDEAS_SPACE_ROMANCE", "ideas_space_romance");
define("IDEAS_SPACE_SCIFI", "ideas_space_scifi");
define("IDEAS_SPACE_SUPERHERO", "ideas_space_superhero");
define("IDEAS_SPACE_WESTERN", "ideas_space_western");
define("PRINTED_COMIC", "printed_comic");
define("SELECTED_ACTION_SPACE", "selected_action_space");
define("TICKET_SUPPLY", "ticket_supply");
define("TOTAL_TURNS", "total_turns");
define("TURNS_TAKEN", "turns_taken");
define("START_IDEAS", "start_ideas");
define("MAX_ACTION_SPACES", "max_action_spaces");

/** Location Keys */
define("LOCATION_VOID", -1);
define("LOCATION_DECK", 1);
define("LOCATION_DISCARD", 2);
define("LOCATION_HAND", 3);
define("LOCATION_PLAYER_AREA", 4);
define("LOCATION_SUPPLY", 5);
define("LOCATION_PLAYER_MAT", 6);
define("LOCATION_CHART", 7);
define("LOCATION_HYPE", 8);
define("LOCATION_EXTRA_EDITOR", 9);
define("LOCATION_MAP", 10);

/** Action Space Keys */
define("LOCATION_ACTION_SPACE_1", 1);
define("LOCATION_ACTION_SPACE_2", 2);
define("LOCATION_ACTION_SPACE_3", 3);
define("LOCATION_ACTION_SPACE_4", 4);
define("LOCATION_ACTION_SPACE_5", 5);

/** Action Location Keys */
define("LOCATION_ACTION_HIRE", 10000);
define("LOCATION_ACTION_DEVELOP", 20000);
define("LOCATION_ACTION_IDEAS", 30000);
define("LOCATION_ACTION_PRINT", 40000);
define("LOCATION_ACTION_ROYALTIES", 50000);
define("LOCATION_ACTION_SALES", 60000);

/** Sales Agent Space Keys */
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

/** Sales Order Space Keys */
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

/** Royalties Space Values */
define("ROYALTIES_AMOUNTS", [
    50001 => 4,
    50002 => 3,
    50003 => 3,
    50004 => 2,
    50005 => 1,
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

define("SALES_ORDERS_FOR_PLAYER_COUNT", [
    2 => [3, 3, 4, 5, 6],
    3 => [3, 3, 4, 4, 5, 6],
    4 => [3, 3, 3, 4, 4, 5, 6],
]);

/** State Args */
define("STATE_ARGS_CHECK_HAND_SIZE", "argsCheckHandSize");
define("STATE_ARGS_COMPLETE_SETUP", "argsCompleteSetup");
define("STATE_ARGS_GAME_END", "argsGameEnd");
define("STATE_ARGS_NEXT_PLAYER", "argsNextPlayer");
define("STATE_ARGS_NEXT_PLAYER_SETUP", "argsNextPlayerSetup");
define("STATE_ARGS_PERFORM_DEVELOP", "argsPerformDevelop");
define("STATE_ARGS_PERFORM_HIRE", "argsPerformHire");
define("STATE_ARGS_PERFORM_IDEAS", "argsPerformIdeas");
define("STATE_ARGS_PERFORM_PRINT", "argsPerformPrint");
define("STATE_ARGS_PERFORM_PRINT_BONUS", "argsPerformPrintBonus");
define("STATE_ARGS_PERFORM_ROYALTIES", "argsPerformRoyalties");
define("STATE_ARGS_PERFORM_SALES", "argsPerformSales");
define("STATE_ARGS_PLAYER_SETUP", "argsPlayerSetup");
define("STATE_ARGS_PLAYER_TURN", "argsPlayerTurn");
define("STATE_ARGS_START_NEW_ROUND", "argsStartNewRound");

/** State IDs */
define("ST_GAME_SETUP", 1);
define("ST_PLAYER_SETUP", 2);
define("ST_NEXT_PLAYER_SETUP", 3);
define("ST_COMPLETE_SETUP", 4);
define("ST_START_NEW_ROUND", 10);
define("ST_PLAYER_TURN", 20);
define("ST_PERFORM_HIRE", 21);
define("ST_PERFORM_DEVELOP", 22);
define("ST_PERFORM_IDEAS", 23);
define("ST_PERFORM_PRINT", 24);
define("ST_PERFORM_PRINT_BONUS", 241);
define("ST_PERFORM_ROYALTIES", 25);
define("ST_PERFORM_SALES", 26);
define("ST_CHECK_HAND_SIZE", 28);
define("ST_NEXT_PLAYER", 29);
define("ST_GAME_END", 99);

/** State Names */
define("CHECK_HAND_SIZE", "checkHandSize");
define("COMPLETE_SETUP", "completeSetup");
define("GAME_END", "gameEnd");
define("GAME_SETUP", "gameSetup");
define("NEXT_PLAYER", "nextPlayer");
define("NEXT_PLAYER_SETUP", "nextPlayerSetup");
define("PERFORM_DEVELOP", "performDevelop");
define("PERFORM_HIRE", "performHire");
define("PERFORM_IDEAS", "performIdeas");
define("PERFORM_PRINT", "performPrint");
define("PERFORM_PRINT_BONUS", "performPrintBonus");
define("PERFORM_ROYALTIES", "performRoyalties");
define("PERFORM_SALES", "performSales");
define("PLAYER_SETUP", "playerSetup");
define("PLAYER_TURN", "playerTurn");
define("START_NEW_ROUND", "startNewRound");

/** State Types */
define("STATE_TYPE_ACTIVE_PLAYER", "activeplayer");
define("STATE_TYPE_GAME", "game");
define("STATE_TYPE_MANAGER", "manager");
define("STATE_TYPE_MULTIPLE_ACTIVE_PLAYER", "multipleactiveplayer");
