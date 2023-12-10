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
 * Handles general game logic on front-end
 *
 */

class GameController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  /**
   * Set up game
   * @param {object} gamedata - current game data used to initialize UI
   */
  setup(gamedata: any) {
    this.createNeededGameElements(gamedata);
  }

  /**
   * Create:
   *  - game status panel
   *  - show chart container
   *  - chart
   * @param {object} gamedata - current game data used to initialize UI
   */
  createNeededGameElements(gamedata: any): void {
    this.createGameStatusPanelHtml();
    this.createShowChartContainerHtml();
    this.createChartHtml(gamedata.playerInfo);
    this.createOnClickEvents();
  }

  /**
   * Creates the game status panel above the player board panels
   */
  createGameStatusPanelHtml(): void {
    var gameStatusPanelHtml =
      '<div id="aoc-game-status-panel" class="player-board"><div id="aoc-game-status" class="player_board_content"><div id="aoc-game-status-mastery-container" class="aoc-game-status-row"></div><div id="aoc-button-row" class="aoc-game-status-row"><a id="aoc-show-chart-button" class="aoc-status-button" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-chart-simple"></i></a><a id="aoc-carousel-button" class="aoc-status-button" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-arrows-left-right-to-line"></i></a><a id="aoc-list-button" class="aoc-status-button" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-list"></i></a></div></div></div>';
    this.ui.createHtml(gameStatusPanelHtml, "player_boards");
  }

  /**
   * Creates the container for the chart
   */
  createShowChartContainerHtml(): void {
    var showChartContainerHtml =
      '<div id="aoc-show-chart-container"><div id="aoc-show-chart-underlay"></div><div id="aoc-show-chart-wrapper"></div></div>';
    this.ui.createHtml(showChartContainerHtml, "overall-content");
  }

  /**
   * Creates the comics chart - only show tracks for players in the game
   * @param {object} players - player info
   */
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
    this.ui.createHtml(chartHtml, "aoc-show-chart-wrapper");
    for (var key in players) {
      var player = players[key];
      this.createChartSpacesHtml(player);
    }
  }

  createChartSpacesHtml(player: any): void {
    var space = 0;
    while (space < 11) {
      var chartFanSpaceHtml =
        '<div id="aoc-chart-space-' +
        player.id +
        "-" +
        space +
        '" class="aoc-chart-space aoc-chart-space-' +
        space +
        '"></div>';
      this.ui.createHtml(chartFanSpaceHtml, "aoc-chart-" + player.id);
      space++;
    }
  }

  /**
   * Creates the on click events for the game
   *
   * - show chart
   * - hide chart
   * - carousel view
   * - list view
   * - next player
   * - previous player
   */
  createOnClickEvents(): void {
    dojo.connect($("aoc-show-chart-button"), "onclick", this, "showChart");
    dojo.connect($("aoc-show-chart-close"), "onclick", this, "hideChart");
    dojo.connect($("aoc-carousel-button"), "onclick", this, "carouselView");
    dojo.connect($("aoc-list-button"), "onclick", this, "listView");
    dojo.query(".fa6-circle-right").connect("onclick", this, "nextPlayer");
    dojo.query(".fa6-circle-left").connect("onclick", this, "previousPlayer");
  }

  /**
   * Show chart
   */
  showChart(): void {
    dojo.style("aoc-show-chart-container", "display", "block");
  }

  /**
   * Hide chart
   */
  hideChart(): void {
    dojo.style("aoc-show-chart-container", "display", "none");
  }

  /**
   * Switch to carousel view
   */
  carouselView(): void {
    var playersSection = dojo.query("#aoc-players-section")[0];
    for (var i = 1; i < playersSection.children.length; i++) {
      var playerSection = playersSection.children[i];
      if (!dojo.hasClass(playerSection, "aoc-hidden")) {
        dojo.toggleClass(playerSection, "aoc-hidden");
      }
    }
    var arrows = dojo.query(".aoc-arrow");
    arrows.forEach(function (arrow: any) {
      if (dojo.hasClass(arrow, "aoc-hidden")) {
        dojo.toggleClass(arrow, "aoc-hidden");
      }
    });
    this.ui.adaptViewportSize();
  }

  /**
   * Switch to list view
   */
  listView(): void {
    var playersSection = dojo.query("#aoc-players-section")[0];
    for (var i = 0; i < playersSection.children.length; i++) {
      var playerSection = playersSection.children[i];
      if (dojo.hasClass(playerSection, "aoc-hidden")) {
        dojo.toggleClass(playerSection, "aoc-hidden");
      }
    }
    var arrows = dojo.query(".aoc-arrow");
    arrows.forEach(function (arrow: any) {
      if (!dojo.hasClass(arrow, "aoc-hidden")) {
        dojo.toggleClass(arrow, "aoc-hidden");
      }
    });
    this.ui.adaptViewportSize();
  }

  /**
   * While in carousel view, switch to next player
   */
  nextPlayer(): void {
    var visiblePlayerSection = dojo.query(
      ".aoc-player-background-panel:not(.aoc-hidden)"
    )[0];
    var visiblePlayerId = visiblePlayerSection.id;
    var playersSection = dojo.query("#aoc-players-section")[0];
    for (var i = 0; i < playersSection.children.length; i++) {
      var playerSection = playersSection.children[i];
      if (playerSection.id == visiblePlayerId) {
        if (i == playersSection.children.length - 1) {
          var nextPlayerSection = playersSection.children[0];
        } else {
          var nextPlayerSection = playersSection.children[i + 1];
        }
      }
    }
    dojo.toggleClass(visiblePlayerSection, "aoc-hidden");
    dojo.toggleClass(nextPlayerSection, "aoc-hidden");
  }

  /**
   * While in carousel view, switch to previous player
   */
  previousPlayer(): void {
    var visiblePlayerSection = dojo.query(
      ".aoc-player-background-panel:not(.aoc-hidden)"
    )[0];
    var visiblePlayerId = visiblePlayerSection.id;
    var playersSection = dojo.query("#aoc-players-section")[0];
    for (var i = 0; i < playersSection.children.length; i++) {
      var playerSection = playersSection.children[i];
      if (playerSection.id == visiblePlayerId) {
        if (i == 0) {
          var previousPlayerSection =
            playersSection.children[playersSection.children.length - 1];
        } else {
          var previousPlayerSection = playersSection.children[i - 1];
        }
      }
    }
    dojo.toggleClass(visiblePlayerSection, "aoc-hidden");
    dojo.toggleClass(previousPlayerSection, "aoc-hidden");
  }
}
