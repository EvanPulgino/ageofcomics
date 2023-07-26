/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * GameController.ts
 *
 */

 class GameController extends GameBasics {

    setup(gamedata: any) {
        this.createIdeaTokensOnBoard(gamedata.ideasSpaceContents);
    }

    createIdeaTokensOnBoard(ideasSpaceContents) {
        for (var key in ideasSpaceContents) {
            var genreSpace = ideasSpaceContents[key];
            this.createIdeaTokenOnBoard(key, genreSpace);
        }
    }

    createIdeaTokenOnBoard(genreId: any, exists: boolean) {
        if (exists) {
            var genre = this.getGenreName(genreId);
            var ideaTokenDiv = '<div id="aoc-idea-token-' + genre + '" class="aoc-idea-token aoc-idea-token-' + genre + '"></div>';
            this.createHtml(ideaTokenDiv,"aoc-action-ideas-" + genre);
        }

    }
 }