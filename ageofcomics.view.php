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
 * ageofcomics.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in ageofcomics_ageofcomics.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */

require_once APP_BASE_PATH . "view/common/game.view.php";

class view_ageofcomics_ageofcomics extends game_view {
    protected function getGameName() {
        // Used for translations and stuff. Please do not modify.
        return "ageofcomics";
    }

    private function createPlayersSection($template, $players, $section) {
        $this->page->begin_block($template, $section);
        foreach ($players as $player) {
            $this->page->insert_block($section, [
                "player_id" => $player->getId(),
                "player_name" => $player->getName(),
                "color" => $player->getColorAsText(),
                "hidden" => $this->isCurrentPlayer($player) ? "" : "aoc-hidden",
            ]);
        }
    }

    private function isCurrentPlayer($player) {
        return $player->getId() == $this->game->getViewingPlayerId();
    }

    function build_page($viewArgs) {
        $template = self::getGameName() . "_" . self::getGameName();

        // Get players & players number
        $players = $this->game->playerManager->getPlayersInViewOrder();
        $players_nbr = count($players);

        $this->tpl["ARTIST_DISCARDS"] = self::_("Discarded Artist Cards");
        $this->tpl["WRITER_DISCARDS"] = self::_("Discarded Writer Cards");
        $this->tpl["COMIC_DISCARDS"] = self::_("Discarded Comic Cards");
        $this->tpl["SELECT_START_ITEMS"] = self::_(
            "Select Starting Comic and Idea Tokens"
        );
        $this->tpl["SELECT_COMIC_TO_PRINT"] = self::_(
            "Select a Comic to Print"
        );
        $this->tpl["SELECT_WRITER"] = self::_("Select a Writer");
        $this->tpl["SELECT_ARTIST"] = self::_("Select an Artist");
        $this->tpl["IMPROVE_CREATIVES"] = self::_("Improve Creatives");
        $this->tpl["SELECT_COMIC_FOR_ORDER"] = self::_(
            "Select Comic to Fulfill Order:"
        );

        $this->createPlayersSection($template, $players, "playerarea");
        $this->createPlayersSection($template, $players, "playerhands");

        /*********** Do not change anything below this line  ************/
    }
}
