-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----
-- dbmodel.sql
-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here
-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.
-- Example 1: create a standard "card" table to be used with the "Deck" tools (see example game "hearts"):
-- CREATE TABLE IF NOT EXISTS `card` (
--   `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
--   `card_type` varchar(16) NOT NULL,
--   `card_type_arg` int(11) NOT NULL,
--   `card_location` varchar(16) NOT NULL,
--   `card_location_arg` int(11) NOT NULL,
--   PRIMARY KEY (`card_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `calendar_tile` (
    `calendar_tile_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `calendar_tile_genre` INT(10) NOT NULL,
    `calendar_tile_round` INT(10) NOT NULL,
    `calendar_tile_position` INT(10) NOT NULL,
    `calendar_tile_class` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`calendar_tile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `editor` (
    `editor_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `editor_owner` INT(10) NOT NULL,
    `editor_location` INT(10) NOT NULL,
    `editor_class` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`editor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `mastery_token` (
    `mastery_token_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `mastery_token_genre` INT(10) NOT NULL,
    `mastery_token_comic_count` INT(10) NOT NULL DEFAULT '0',
    `mastery_token_owner` INT(10) DEFAULT NULL,
    PRIMARY KEY (`mastery_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `sales_order` (
    `sales_order_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `sales_order_genre` INT(10) NOT NULL,
    `sales_order_value` INT(10) NOT NULL,
    `sales_order_fans` INT(10) NOT NULL,
    `sales_order_owner` INT(10) DEFAULT NULL,
    `sales_order_location` INT(10) NOT NULL,
    `sales_order_class` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `player`
ADD `player_turn_order` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_money` INT(10) UNSIGNED NOT NULL DEFAULT '5';
ALTER TABLE `player`
ADD `player_crime_ideas` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_horror_ideas` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_romance_ideas` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_scifi_ideas` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_superhero_ideas` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_western_ideas` INT(10) UNSIGNED NOT NULL DEFAULT '0';