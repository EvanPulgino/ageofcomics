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
}