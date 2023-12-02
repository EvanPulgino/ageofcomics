/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * RipoffController.ts
 *
 * Handles ripoff card logic on front-end
 *
 */

class RipoffController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  /**
   * Set up ripoff cards
   * @param {object} ripoffCards - current ripoff card data used to initialize UI
   */
  setupRipoffCards(ripoffCards: any): void {
    for (var key in ripoffCards) {
      this.createRipoffCard(ripoffCards[key]);
    }
  }

  /**
   * Creates a ripoff card
   * @param {object} ripoffCard - ripoff card data
   */
  createRipoffCard(ripoffCard: any): void {
    var ripoffCardDiv =
      '<div id="aoc-ripoff-card-' +
      ripoffCard.id +
      '" class="aoc-ripoff-card ' +
      ripoffCard.cssClass +
      '"></div>';
    if (ripoffCard.location == globalThis.LOCATION_DECK) {
      this.ui.createHtml(ripoffCardDiv, "aoc-ripoff-deck");
    }
  }
}
