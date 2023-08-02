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
  
require_once( APP_BASE_PATH."view/common/game.view.php" );
  
class view_ageofcomics_ageofcomics extends game_view
{
    protected function getGameName()
    {
        // Used for translations and stuff. Please do not modify.
        return "ageofcomics";
    }

    private function getColorString($hexColor) {
        switch($hexColor) {
            case '8e514e':
                return 'brown';
            case 'e5977a':
                return 'salmon';
            case '5ba59f':
                return 'teal';
            case 'f5c86e':
                return 'yellow';
        }
        return '';
    }

    private function createPlayersSection($template, $players, $section) {
        $this->page->begin_block($template, $section);
        foreach($players as $player) {
            $this->page->insert_block($section, array(
                "player_id" => $player->getId(),
                "player_name" => $player->getName(),
                "color" => $player->getColorAsText(),
            ));
        }
    }
    
  	function build_page( $viewArgs )
  	{	
        $template = self::getGameName() . "_" . self::getGameName();

  	    // Get players & players number
        $players = $this->game->playerManager->getPlayersInViewOrder();
        $players_nbr = count( $players );

        $this->createPlayersSection($template, $players, "playerchart");
        $this->createPlayersSection($template, $players, "playerarea");

        /*********** Do not change anything below this line  ************/
  	}
}
