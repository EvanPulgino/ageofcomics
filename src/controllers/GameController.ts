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
    this.createNeededGameElements(gamedata);
    this.createIdeaTokensOnBoard(gamedata.ideasSpaceContents);
  }

  /**
   * Create game status panel
   * @param {object} gamedata - current game data used to initialize UI
   */
  createNeededGameElements(gamedata: any): void {
    this.createGameStatusPanelHtml();
    this.createShowChartContainerHtml();
    this.createChartHtml(gamedata.playerInfo);
    this.createOnClickEvents();
  }

  createGameStatusPanelHtml(): void {
    var gameStatusPanelHtml =
      '<div id="aoc-game-status-panel" class="player-board"><div id="aoc-game-status" class="player_board_content"><div id="aoc-game-status-mastery-container" class="aoc-game-status-row"></div><div id="aoc-chart-button" class="aoc-game-status-row"><a id="aoc-show-chart-button" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-chart-simple"></i></a><i class="aoc-icon-size fa6 fa6-solid fa6-arrows-left-right-to-line"></i><i class="aoc-icon-size fa6 fa6-solid fa6-list"></i></div></div></div>';
    this.createHtml(gameStatusPanelHtml, "player_boards");
  }

  createShowChartContainerHtml(): void {
    var showChartContainerHtml =
      '<div id="aoc-show-chart-container"><div id="aoc-show-chart-underlay"></div><div id="aoc-show-chart-wrapper"></div></div>';
    this.createHtml(showChartContainerHtml, "overall-content");
  }

  createChartHtml(players: any): void {
    var chartWidth = 87 + players.length * 71;
    var chartHtml =
      '<div id="aoc-show-chart" style="width: ' +
      chartWidth +
      '"><a id="aoc-show-chart-close" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-square-xmark fa6-2x aria-hidden="true"></i></a><div id="aoc-chart" class="aoc-board-section"><div id="aoc-chart-start" class="aoc-board-image aoc-chart-start"></div>';
    for (var key in players) {
      var player = players[key];
      chartHtml +=
        '<div id="aoc-chart-' +
        player.id +
        '" class="aoc-board-image aoc-chart-' +
        player.colorAsText +
        '"></div>';
    }
    chartHtml +=
      '<div id="aoc-chart-end" class="aoc-board-image aoc-chart-end"></div></div></div>';
    console.log(chartHtml);
    this.createHtml(chartHtml, "aoc-show-chart-wrapper");
  }

  createOnClickEvents(): void {
    dojo.connect(
      $("aoc-show-chart-button"),
      "onclick",
      this,
      "showChart"
    );
    dojo.connect(
      $("aoc-show-chart-close"),
      "onclick",
      this,
      "hideChart"
    );
  }

  createIdeaTokensOnBoard(ideasSpaceContents: any) {
    for (var key in ideasSpaceContents) {
      var genreSpace = ideasSpaceContents[key];
      this.createIdeaTokenOnBoard(key, genreSpace);
    }
  }

  createIdeaTokenOnBoard(genreId: any, exists: boolean) {
    if (exists) {
      var genre = this.getGenreName(genreId);
      var ideaTokenDiv =
        '<div id="aoc-idea-token-' +
        genre +
        '" class="aoc-idea-token aoc-idea-token-' +
        genre +
        '"></div>';
      this.createHtml(ideaTokenDiv, "aoc-action-ideas-" + genre);
    }
  }

  showChart(): void {
    dojo.style("aoc-show-chart-container", "display", "block");
  }

  hideChart(): void {
    dojo.style("aoc-show-chart-container", "display", "none");
  }
}
