/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformPrintMastery.ts
 *
 * AgeOfComics perform print upgrade state
 *
 * State vars:
 * - game: game object reference
 *
 */
class PerformPrintUpgrade implements State {
  game: any;
  connections: any;
  placedCubes: number;
  cubeToMove: number;
  constructor(game: any) {
    this.game = game;
    this.connections = {};
    this.placedCubes = 0;
    this.cubeToMove = 0;
  }

  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      const player = stateArgs.args.player;
      if (player.cubeOneLocation > 0) {
        this.placedCubes++;
      }
      if (player.cubeTwoLocation > 0) {
        this.placedCubes++;
      }
      if (player.cubeThreeLocation > 0) {
        this.placedCubes++;
      }
      if (this.placedCubes < 3) {
        this.cubeToMove = this.placedCubes + 1;
      }
      if (this.placedCubes == 3 && stateArgs.args.upgradeCubeToUse > 0) {
        this.cubeToMove = this.getCubeToMove(
          player,
          stateArgs.args.upgradeCubeToUse
        );
      }
      this.highlightUpgradableActions(stateArgs.args.upgradableActions);
    }
  }
  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");
    for (const connection of Object.values(this.connections)) {
      dojo.disconnect(connection);
    }
  }

  onUpdateActionButtons(stateArgs: any): void {}

  getCubeToMove(player: any, cubeLocation: number) {
    if (player.cubeOneLocation === cubeLocation) {
      return 1;
    } else if (player.cubeTwoLocation === cubeLocation) {
      return 2;
    } else if (player.cubeThreeLocation === cubeLocation) {
      return 3;
    }
    return 0;
  }

  highlightUpgradableActions(upgradableActions: any[]): void {
    for (const key in upgradableActions) {
      const action = upgradableActions[key];
      switch (action) {
        case 1:
          this.highlightUpgradableAction(
            action,
            "aoc-action-hire-upgrade-spaces"
          );
          break;
        case 2:
          this.highlightUpgradableAction(
            action,
            "aoc-action-develop-upgrade-spaces"
          );
          break;
        case 3:
          this.highlightUpgradableAction(
            action,
            "aoc-action-ideas-upgrade-spaces"
          );
          break;
        case 4:
          this.highlightUpgradableAction(
            action,
            "aoc-action-print-upgrade-spaces"
          );
          break;
        case 5:
          this.highlightUpgradableAction(
            action,
            "aoc-action-royalties-upgrade-spaces"
          );
          break;
        case 6:
          this.highlightUpgradableAction(
            action,
            "aoc-action-sales-upgrade-spaces"
          );
          break;
      }
    }
  }

  highlightUpgradableAction(actionKey: number, divId: any): void {
    dojo.toggleClass(divId, "aoc-clickable", true);
    this.connections[actionKey] = dojo.connect(
      dojo.byId(divId),
      "onclick",
      dojo.hitch(this, "upgradeAction", actionKey)
    );
  }

  upgradeAction(actionKey: number): void {
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_PLACE_UPGRADE_CUBE, {
      actionKey: actionKey,
      cubeMoved: this.cubeToMove,
    });
  }
}
