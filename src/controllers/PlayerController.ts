/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PlayerController.ts
 *
 */

class PlayerController {
  ui: any;
  playerCounter: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  setupPlayers(playerData: any): void {
    this.playerCounter = {};
    for (var key in playerData) {
      this.createPlayerOrderToken(playerData[key]);
      this.createPlayerAgent(playerData[key]);
      this.createPlayerCubes(playerData[key]);
      this.createPlayerPanel(playerData[key]);
      this.createPlayerCounters(playerData[key]);
    }
  }

  adjustMoney(player: any, amount: number): void {
    this.updatePlayerCounter(player.id, "money", amount);
  }

  createStartingIdeaToken(genre: string): any {
    var randomId = Math.floor(Math.random() * 1000000);
    var ideaTokenDiv =
      '<div id="' +
      randomId +
      '" class="aoc-idea-token aoc-idea-token-' +
      genre +
      '" style="position:relative;z-index:1000;"></div>';
    return this.ui.createHtml(
      ideaTokenDiv,
      "aoc-select-starting-idea-" + genre
    );
  }

  createSupplyIdeaToken(genre: string): any {
    var randomId = Math.floor(Math.random() * 1000000);
    var ideaTokenDiv =
      '<div id="' +
      randomId +
      '" class="aoc-idea-token aoc-idea-token-' +
      genre +
      '" style="position:relative;z-index:1000;"></div>';
    return this.ui.createHtml(
      ideaTokenDiv,
      "aoc-select-supply-idea-token-" + genre
    );
  }

  createPlayerOrderToken(player: any): void {
    var playerOrderTokenDiv =
      '<div id="aoc-player-order-token' +
      player.id +
      '" class="aoc-player-order-token aoc-player-order-token-' +
      player.colorAsText +
      '"></div>';
    this.ui.createHtml(
      playerOrderTokenDiv,
      "aoc-player-order-space-" + player.turnOrder
    );
  }

  createPlayerAgent(player: any): void {
    var playerAgentDiv =
      '<div id="aoc-agent' +
      player.id +
      '" class="aoc-agent aoc-agent-' +
      player.colorAsText +
      '"></div>';
    this.ui.createHtml(
      playerAgentDiv,
      "aoc-map-agent-space-" + player.agentLocation
    );
  }

  createPlayerCubes(player: any): void {
    this.createPlayerCubeOne(player);
    this.createPlayerCubeTwo(player);
    this.createPlayerCubeThree(player);
  }

  createPlayerCubeOne(player: any): void {
    var cubeDiv =
      '<div id="aoc-player-cube-one-' +
      player.id +
      '" class="aoc-player-cube aoc-player-cube-' +
      player.colorAsText +
      '"></div>';
    if (player.cubeOneLocation == 5) {
      this.ui.createHtml(cubeDiv, "aoc-cube-one-space-" + player.id);
    }
  }

  createPlayerCubeTwo(player: any): void {
    var cubeDiv =
      '<div id="aoc-player-cube-two-' +
      player.id +
      '" class="aoc-player-cube aoc-player-cube-' +
      player.colorAsText +
      '"></div>';
    if (player.cubeOneLocation == 5) {
      this.ui.createHtml(cubeDiv, "aoc-cube-two-space-" + player.id);
    }
  }

  createPlayerCubeThree(player: any): void {
    var cubeDiv =
      '<div id="aoc-player-cube-three-' +
      player.id +
      '" class="aoc-player-cube aoc-player-cube-' +
      player.colorAsText +
      '"></div>';
    if (player.cubeOneLocation == 5) {
      this.ui.createHtml(cubeDiv, "aoc-cube-three-space-" + player.id);
    }
  }

  createPlayerPanel(player: any): void {
    var playerPanelDiv =
      '<div id="aoc-player-panel-' +
      player.id +
      '" class="aoc-player-panel">' +
      '<div id="aoc-player-panel-mastery-' +
      player.id +
      '" class="aoc-player-panel-row aoc-player-panel-mastery"></div>' +
      '<div id="aoc-player-panel-ideas-1-' +
      player.id +
      '" class="aoc-player-panel-row">' +
      this.createPlayerPanelIdeaSupplyDiv(player, "crime") +
      this.createPlayerPanelIdeaSupplyDiv(player, "horror") +
      this.createPlayerPanelIdeaSupplyDiv(player, "romance") +
      "</div>" +
      '<div id="aoc-player-panel-ideas-2-' +
      player.id +
      '" class="aoc-player-panel-row">' +
      this.createPlayerPanelIdeaSupplyDiv(player, "scifi") +
      this.createPlayerPanelIdeaSupplyDiv(player, "superhero") +
      this.createPlayerPanelIdeaSupplyDiv(player, "western") +
      "</div>" +
      '<div id="aoc-player-panel-other-' +
      player.id +
      '" class="aoc-player-panel-row">' +
      this.createPlayerPanelOtherSupplyDiv(player, "money") +
      this.createPlayerPanelOtherSupplyDiv(player, "point") +
      this.createPlayerPanelOtherSupplyDiv(player, "income") +
      "</div>" +
      "</div>";
    this.ui.createHtml(playerPanelDiv, "player_board_" + player.id);
  }

  createPlayerPanelIdeaSupplyDiv(player: any, genre: string): any {
    var ideaSupplyDiv =
      '<div id="aoc-player-panel-' +
      genre +
      "-supply-" +
      player.id +
      '" class="aoc-player-panel-supply aoc-player-panel-idea-supply"><span id="aoc-player-panel-' +
      genre +
      "-count-" +
      player.id +
      '" class="aoc-player-panel-supply-count aoc-squada"></span><span id="aoc-player-panel-' +
      genre +
      "-" +
      player.id +
      '" class="aoc-idea-token aoc-idea-token-' +
      genre +
      '"></span></div>';
    return ideaSupplyDiv;
  }

  createPlayerPanelOtherSupplyDiv(player: any, supply: string): any {
    var otherSupplyDiv;
    switch (supply) {
      case "money":
        otherSupplyDiv =
          '<div id="aoc-player-panel-money-' +
          player.id +
          '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-money-count-' +
          player.id +
          '" class="aoc-player-panel-supply-count aoc-squada"></span><i id="aoc-player-panel-money-' +
          player.id +
          '" class="aoc-round-token aoc-token-coin"></i></div>';
        break;
      case "point":
        otherSupplyDiv =
          '<div id="aoc-player-panel-point-' +
          player.id +
          '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-point-count-' +
          player.id +
          '" class="aoc-player-panel-supply-count aoc-squada"></span><span id="aoc-player-panel-points-' +
          player.id +
          '" class="aoc-round-token aoc-token-point"></span></div>';
        break;
      case "income":
        otherSupplyDiv =
          '<div id="aoc-player-panel-income-' +
          player.id +
          '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-income-count-' +
          player.id +
          '" class="aoc-player-panel-income-count aoc-squada"></span><i id="aoc-player-panel-income-' +
          player.id +
          '" class="aoc-player-panel-icon-size fa6 fa6-solid fa6-money-bill-trend-up"></i></div>';
        break;
    }
    return otherSupplyDiv;
  }

  createPlayerCounters(player: any): void {
    this.playerCounter[player.id] = {};
    this.createPlayerCounter(player, "crime", player.crimeIdeas);
    this.createPlayerCounter(player, "horror", player.horrorIdeas);
    this.createPlayerCounter(player, "romance", player.romanceIdeas);
    this.createPlayerCounter(player, "scifi", player.scifiIdeas);
    this.createPlayerCounter(player, "superhero", player.superheroIdeas);
    this.createPlayerCounter(player, "western", player.westernIdeas);
    this.createPlayerCounter(player, "money", player.money);
    this.createPlayerCounter(player, "point", player.score);
    // TODO:calculate income
    this.createPlayerCounter(player, "income", 0);
  }

  createPlayerCounter(
    player: any,
    counter: string,
    initialValue: number
  ): void {
    var counterKey = counter;
    var counterPanel = "panel-" + counter;

    this.playerCounter[player.id][counterKey] = new ebg.counter();
    this.playerCounter[player.id][counterKey].create(
      "aoc-player-" + counter + "-count-" + player.id
    );
    this.playerCounter[player.id][counterKey].setValue(initialValue);

    this.playerCounter[player.id][counterPanel] = new ebg.counter();
    this.playerCounter[player.id][counterPanel].create(
      "aoc-player-" + counterPanel + "-count-" + player.id
    );
    this.playerCounter[player.id][counterPanel].setValue(initialValue);
  }

  gainIdeaFromBoard(playerId: any, genre: string): void {
    var ideaTokenDiv = dojo.byId("aoc-idea-token-" + genre);
    var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
    gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
    this.updatePlayerCounter(playerId, genre, 1);
  }

  gainIdeaFromSupply(playerId: any, genre: string): void {
    var ideaTokenDiv = this.createSupplyIdeaToken(genre);
    var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
    gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
    this.updatePlayerCounter(playerId, genre, 1);
  }

  gainStartingIdea(playerId: any, genre: string): void {
    var ideaTokenDiv = this.createStartingIdeaToken(genre);
    var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
    gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
    this.updatePlayerCounter(playerId, genre, 1);
  }

  updatePlayerCounter(playerId: any, counter: string, value: number): void {
    var counterKey = counter;
    var counterPanel = "panel-" + counter;
    this.playerCounter[playerId][counterKey].incValue(value);
    this.playerCounter[playerId][counterPanel].incValue(value);
  }
}
