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
 * Handles mastery token logic on front-end
 *
 */

class MasteryController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  /**
   * Set up mastery tokens
   * @param {object} masteryTokens - current mastery token data used to initialize UI
   */
  setupMasteryTokens(masteryTokens: any): void {
    for (var key in masteryTokens) {
      this.createMasteryToken(masteryTokens[key]);
    }
  }

  /**
   * Creates a mastery token
   * @param {object} masteryToken - mastery token data
   */
  createMasteryToken(masteryToken: any): void {
    var masteryTokenDiv =
      '<div id="aoc-mastery-token-' +
      masteryToken.id +
      '" class="aoc-mastery-token aoc-mastery-token-' +
      masteryToken.genre +
      '"></div>';
    if (masteryToken.playerId == 0) {
      this.ui.createHtml(masteryTokenDiv, "aoc-game-status-mastery-container");
    }
  }
}
