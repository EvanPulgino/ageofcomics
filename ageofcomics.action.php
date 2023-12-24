<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 *
 * ageofcomics.action.php
 *
 * AgeOfComics main action entry point
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/ageofcomics/ageofcomics/myAction.html", ...)
 *
 * @link https://en.doc.boardgamearena.com/Players_actions:_yourgamename.action.php
 *
 * @EvanPulgino
 */

class action_ageofcomics extends APP_GameAction {
    // Constructor: please do not modify
    public function __default() {
        if (self::isArg("notifwindow")) {
            $this->view = "common_notifwindow";
            $this->viewArgs["table"] = self::getArg("table", AT_posint, true);
        } else {
            $this->view = "ageofcomics_ageofcomics";
            self::trace("Complete reinitialization of board game");
        }
    }

    public function confirmDiscard() {
        self::setAjaxMode();

        $cardsToDiscard = self::getArg("cardsToDiscard", AT_numberlist, true);

        $this->game->states[CHECK_HAND_SIZE]->confirmDiscard(
            explode(",", $cardsToDiscard)
        );

        self::ajaxResponse();
    }

    public function confirmGainIdeas() {
        self::setAjaxMode();

        $ideasFromBoard = self::getArg("ideasFromBoard", AT_numberlist, true);
        $ideasFromSupply = self::getArg("ideasFromSupply", AT_numberlist, true);

        $this->game->states[PERFORM_IDEAS]->confirmGainIdeas(
            explode(",", $ideasFromBoard),
            explode(",", $ideasFromSupply)
        );

        self::ajaxResponse();
    }

    public function confirmGainBonusIdeas() {
        self::setAjaxMode();

        $ideas = self::getArg("ideas", AT_numberlist, true);

        $this->game->states[PERFORM_PRINT_BONUS]->confirmGainBonusIdeas(
            explode(",", $ideas)
        );

        self::ajaxResponse();
    }

    public function developComic() {
        self::setAjaxMode();

        $comicId = self::getArg("comicId", AT_posint, true);
        $topOfDeck = self::getArg("topOfDeck", AT_bool, true);

        $this->game->states[PERFORM_DEVELOP]->developComic(
            $comicId,
            $topOfDeck
        );

        self::ajaxResponse();
    }

    public function developFromGenre() {
        self::setAjaxMode();

        $genre = self::getArg("genre", AT_alphanum, true);

        $this->game->states[PERFORM_DEVELOP]->developFromGenre($genre);

        self::ajaxResponse();
    }

    public function hireCreative() {
        self::setAjaxMode();

        $cardId = self::getArg("cardId", AT_posint, true);
        $creativeType = self::getArg("creativeType", AT_alphanum, true);

        $this->game->states[PERFORM_HIRE]->hireCreative($cardId, $creativeType);

        self::ajaxResponse();
    }

    public function placeUpgradeCube() {
        self::setAjaxMode();

        $actionKey = self::getArg("actionKey", AT_posint, true);
        $cubeMoved = self::getArg("cubeMoved", AT_posint, true);

        $this->game->states[PERFORM_PRINT_UPGRADE]->placeUpgradeCube(
            $actionKey,
            $cubeMoved
        );

        self::ajaxResponse();
    }

    public function printComic() {
        self::setAjaxMode();

        $comicId = self::getArg("comicId", AT_posint, true);
        $artistId = self::getArg("artistId", AT_posint, true);
        $writerId = self::getArg("writerId", AT_posint, true);

        $this->game->states[PERFORM_PRINT]->printComic(
            $comicId,
            $artistId,
            $writerId
        );

        self::ajaxResponse();
    }

    public function selectActionSpace() {
        self::setAjaxMode();

        $actionSpace = self::getArg("actionSpace", AT_posint, true);

        $this->game->states[PLAYER_TURN]->selectActionSpace($actionSpace);

        self::ajaxResponse();
    }

    public function selectStartItems() {
        self::setAjaxMode();

        $comicGenre = self::getArg("comic", AT_posint, true);
        $ideaGenres = self::getArg("ideas", AT_numberlist, true);

        $this->game->states[PLAYER_SETUP]->selectStartItems(
            $comicGenre,
            explode(",", $ideaGenres)
        );

        self::ajaxResponse();
    }

    public function skipDoublePrint() {
        self::setAjaxMode();

        $this->game->states[PERFORM_PRINT]->skipDoublePrint();

        self::ajaxResponse();
    }

    public function takeRoyalties() {
        self::setAjaxMode();

        $amount = self::getArg("amount", AT_posint, true);
        $space = self::getArg("space", AT_posint, true);

        $this->game->takeRoyalties($amount, $space);

        self::ajaxResponse();
    }
}
