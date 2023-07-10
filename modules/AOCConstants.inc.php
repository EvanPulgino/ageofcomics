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
