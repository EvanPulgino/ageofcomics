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

class PlayerController extends GameBasics {
  setupPlayers(playerData: any): void {
    for (var key in playerData) {
      this.createPlayerOrderToken(playerData[key]);
      this.createPlayerAgent(playerData[key]);
      this.createPlayerCubes(playerData[key]);
      this.createPlayerPanel(playerData[key]);
    }
  }

  createPlayerOrderToken(player: any): void {
    this.debug("creating player order token", player);
    var playerOrderTokenDiv =
      '<div id="aoc-player-order-token' +
      player.id +
      '" class="aoc-player-order-token aoc-player-order-token-' +
      player.colorAsText +
      '"></div>';
    this.createHtml(
      playerOrderTokenDiv,
      "aoc-player-order-space-" + player.turnOrder
    );
  }

  createPlayerAgent(player: any): void {
    this.debug("creating player agent", player);
    var playerAgentDiv =
      '<div id="aoc-agent' +
      player.id +
      '" class="aoc-agent aoc-agent-' +
      player.colorAsText +
      '"></div>';
    this.createHtml(
      playerAgentDiv,
      "aoc-map-agent-space-" + player.agentLocation
    );
  }

  createPlayerCubes(player: any): void {
    this.debug("creating player cubes", player);
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
      this.createHtml(cubeDiv, "aoc-cube-one-space-" + player.id);
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
      this.createHtml(cubeDiv, "aoc-cube-two-space-" + player.id);
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
      this.createHtml(cubeDiv, "aoc-cube-three-space-" + player.id);
    }
  }

  createPlayerPanel(player: any): void {
    this.debug("creating player panel", player);
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
    this.createHtml(playerPanelDiv, "player_board_" + player.id);
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
      '" class="aoc-player-panel-supply-count aoc-squada">0</span><span id="aoc-player-panel-' +
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
          '" class="aoc-player-panel-supply-count aoc-squada">5</span><i id="aoc-player-panel-money-' +
          player.id +
          '" class="aoc-round-token aoc-token-coin"></i></div>';
        break;
      case "point":
        otherSupplyDiv =
          '<div id="aoc-player-panel-point-' +
          player.id +
          '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-point-count-' +
          player.id +
          '" class="aoc-player-panel-supply-count aoc-squada">0</span><span id="aoc-player-panel-points-' +
          player.id +
          '" class="aoc-round-token aoc-token-point"></span></div>';
        break;
      case "income":
        otherSupplyDiv =
          '<div id="aoc-player-panel-income-' +
          player.id +
          '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-income-count-' +
          player.id +
          '" class="aoc-player-panel-income-count aoc-squada">0</span><i id="aoc-player-panel-income-' +
          player.id +
          '" class="aoc-player-panel-icon-size fa6 fa6-solid fa6-money-bill-trend-up"></i></div>';
        break;
    }
    return otherSupplyDiv;
  }
}
