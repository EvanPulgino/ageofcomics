/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * MiniComicController.ts
 *
 */

class MiniComicController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  setupMiniComics(miniComics: any): void {
    for (var key in miniComics) {
      this.createMiniComic(miniComics[key]);
    }
  }

  createMiniComic(miniComic: any): void {
    var miniComicDiv =
      '<div id="aoc-mini-comic-' +
      miniComic.id +
      '" class="aoc-mini-comic ' +
      miniComic.cssClass +
      '"></div>';
    if (miniComic.location == globalThis.LOCATION_SUPPLY) {
      this.ui.createHtml(
        miniComicDiv,
        "aoc-mini-" + miniComic.type + "s-" + miniComic.genre
      );
    }
    if (miniComic.location == globalThis.LOCATION_CHART) {
      // TODO: Add to chart location
    }
  }
}
