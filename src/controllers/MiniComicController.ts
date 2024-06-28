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
 * Handles mini comic logic on front-end
 *
 */

class MiniComicController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  /**
   * Set up mini comics
   * @param {object} miniComics - current mini comic data used to initialize UI
   */
  setupMiniComics(miniComics: any): void {
    for (var key in miniComics) {
      this.createMiniComic(miniComics[key]);
    }
  }

  /**
   * Creates a mini comic
   * @param {object} miniComic - mini comic data
   */
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
      const space = miniComic.fans > 10 ? miniComic.fans - 10 : miniComic.fans;

      this.ui.createHtml(
        miniComicDiv,
        "aoc-chart-space-" + miniComic.playerId + "-" + space
      );
    }
  }

  moveMiniComic(miniComic: any): void {
    const fansSpace =
      miniComic.fans > 10 ? miniComic.fans - 10 : miniComic.fans;
    const miniComicDiv = dojo.byId("aoc-mini-comic-" + miniComic.id);

    if (miniComic.fans > 10) {
      if (!dojo.hasClass(miniComicDiv, miniComic.cssClass)) {
        const unflippedClass = miniComic.cssClass.replace("-flipped", "");
        dojo.removeClass(miniComicDiv, unflippedClass);
        dojo.addClass(miniComicDiv, miniComic.cssClass);
      }
    }

    const chartSpaceDiv = dojo.byId(
      "aoc-chart-space-" + miniComic.playerId + "-" + fansSpace
    );
    const animation = gameui.slideToObject(miniComicDiv, chartSpaceDiv, 500);
    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(miniComicDiv, "style");
      dojo.place(miniComicDiv, chartSpaceDiv);
    });
    animation.play();
  }

  moveMiniComicToChart(miniComic: any): void {
    const miniComicDiv = dojo.byId("aoc-mini-comic-" + miniComic.id);
    const chartSpaceDiv = dojo.byId(
      "aoc-chart-space-" + miniComic.playerId + "-" + miniComic.fans
    );

    const animation = gameui.slideToObject(miniComicDiv, chartSpaceDiv, 500);
    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(miniComicDiv, "style");
      dojo.place(miniComicDiv, chartSpaceDiv);
    });
    animation.play();
  }
}
