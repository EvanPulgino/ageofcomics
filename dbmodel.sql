-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----
-- dbmodel.sql

CREATE TABLE IF NOT EXISTS `calendar_tile` (
    `calendar_tile_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `calendar_tile_genre` INT(10) NOT NULL,
    `calendar_tile_round` INT(10) NOT NULL,
    `calendar_tile_position` INT(10) NOT NULL,
    `calendar_tile_flipped` TINYINT DEFAULT 0,
    PRIMARY KEY (`calendar_tile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `card` (
    `card_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `card_type` INT(10) NOT NULL,
    `card_type_arg` INT(10) NOT NULL,
    `card_genre` INT(10) NOT NULL,
    `card_location` INT(10) NOT NULL,
    `card_location_arg` INT(10) DEFAULT NULL,
    `card_owner` INT(10) DEFAULT NULL,
    PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `editor` (
    `editor_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `editor_owner` INT(10) NOT NULL,
    `editor_color` VARCHAR(12) NOT NULL,
    `editor_location` INT(10) NOT NULL,
    PRIMARY KEY (`editor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `mastery_token` (
    `mastery_token_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `mastery_token_genre` INT(10) NOT NULL,
    `mastery_token_comic_count` INT(10) NOT NULL DEFAULT '0',
    `mastery_token_owner` INT(10) DEFAULT NULL,
    PRIMARY KEY (`mastery_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `mini_comic` (
    `mini_comic_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `mini_comic_type` INT(10) NOT NULL,
    `mini_comic_type_arg` INT(10) NOT NULL,
    `mini_comic_genre` INT(10) NOT NULL,
    `mini_comic_location` INT(10) NOT NULL,
    `mini_comic_location_arg` INT(10) DEFAULT NULL,
    `mini_comic_owner` INT(10) DEFAULT NULL,
    `mini_comic_fans` INT(10) DEFAULT NULL,
    PRIMARY KEY (`mini_comic_id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `sales_order` (
    `sales_order_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `sales_order_genre` INT(10) NOT NULL,
    `sales_order_value` INT(10) NOT NULL,
    `sales_order_fans` INT(10) NOT NULL,
    `sales_order_owner` INT(10) DEFAULT NULL,
    `sales_order_location` INT(10) NOT NULL,
    `sales_order_location_arg` INT(10) DEFAULT NULL,
    `sales_order_flipped` TINYINT DEFAULT 0,
    PRIMARY KEY (`sales_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `player`
ADD `player_turn_order` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_money` INT(10) UNSIGNED NOT NULL DEFAULT '5';
ALTER TABLE `player`
ADD `player_income` INT(10) UNSIGNED NOT NULL DEFAULT '0';
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
ALTER TABLE `player`
ADD `player_tickets` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_agent_location` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_agent_arrived` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_cube_one_location` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_cube_two_location` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player`
ADD `player_cube_three_location` INT(10) UNSIGNED NOT NULL DEFAULT '0';