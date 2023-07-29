/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * MasteryController.ts
 *
 */

class MasteryController extends GameBasics {

    setupMasteryTokens(masteryTokens: any) : void {
        for (var key in masteryTokens) {
            this.createMasteryToken(masteryTokens[key]);
        }
    }

    createMasteryToken(masteryToken: any) : void {
        var masteryTokenDiv = '<div id="aoc-mastery-token-'+masteryToken.id+'" class="aoc-mastery-token aoc-mastery-token-'+masteryToken.genre+'"></div>';
        if(masteryToken.playerId == 0) {
            this.createHtml(masteryTokenDiv, "aoc-mastery-tokens");
        }
    }
}