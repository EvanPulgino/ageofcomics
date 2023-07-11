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
 * AOCObject.class.php
 *
 * Generic object class to give objects access to helper functions
 *
 */

class AOCObject {
    /**
     * Get the name of a genre from its key
     * @param $key int
     * @return string
     */
    function getGenreName($key) {
        switch ($key) {
            case GENRE_CRIME:
                return clienttranslate(CRIME);
            case GENRE_HORROR:
                return clienttranslate(HORROR);
            case GENRE_ROMANCE:
                return clienttranslate(ROMANCE);
            case GENRE_SCIFI:
                return clienttranslate(SCIFI);
            case GENRE_SUPERHERO:
                return clienttranslate(SUPERHERO);
            case GENRE_WESTERN:
                return clienttranslate(WESTERN);
            default:
                return "";
        }
    }
}
