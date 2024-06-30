/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformPrintGetUpgradeCube.ts
 *
 * AgeOfComics perform print get upgrade cube state
 *
 * State vars:
 * - game: game object reference
 *
 */
class PerformPrintGetUpgradeCube implements State {
  game: any;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = {};
  }

  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      const player = stateArgs.args.player;
      const spacesWithCubes = this.getSpacesWithCubes(player);
      this.highlightSelectableLocations(spacesWithCubes);
    }
  }
  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");
    for (const connection of Object.values(this.connections)) {
      dojo.disconnect(connection);
    }
  }

  onUpdateActionButtons(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      // Add skip button
      gameui.addActionButton("skip-button", _("Skip Upgrade"), () => {
        this.skipUpgrade();
      });

      dojo.addClass("skip-button", "aoc-button");
    }
  }

  getSpacesWithCubes(player: any): number[] {
    const spacesWithCubes = [];
    spacesWithCubes.push(parseInt(player.cubeOneLocation));
    spacesWithCubes.push(parseInt(player.cubeTwoLocation));
    spacesWithCubes.push(parseInt(player.cubeThreeLocation));

    return spacesWithCubes;
  }

  highlightSelectableLocations(spacesWithCubes: number[]): void {
    console.log(spacesWithCubes);
    for (const locationKey of spacesWithCubes) {
      switch (locationKey) {
        case 1:
          this.highlightSelectableCubeLocation(
            locationKey,
            "aoc-action-hire-upgrade-spaces"
          );
          break;
        case 2:
          this.highlightSelectableCubeLocation(
            locationKey,
            "aoc-action-develop-upgrade-spaces"
          );
          break;
        case 3:
          this.highlightSelectableCubeLocation(
            locationKey,
            "aoc-action-ideas-upgrade-spaces"
          );
          break;
        case 4:
          this.highlightSelectableCubeLocation(
            locationKey,
            "aoc-action-print-upgrade-spaces"
          );
          break;
        case 5:
          this.highlightSelectableCubeLocation(
            locationKey,
            "aoc-action-royalties-upgrade-spaces"
          );
          break;
        case 6:
          this.highlightSelectableCubeLocation(
            locationKey,
            "aoc-action-sales-upgrade-spaces"
          );
          break;
      }
    }
  }

  highlightSelectableCubeLocation(actionKey: number, divId: any): void {
    dojo.toggleClass(divId, "aoc-clickable", true);
    this.connections[actionKey] = dojo.connect(
      dojo.byId(divId),
      "onclick",
      dojo.hitch(this, "selectCubeLocation", actionKey)
    );
  }

  selectCubeLocation(actionKey: number): void {
    this.onLeavingState();
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_SELECT_UPGRADE_CUBE, {
      actionKey,
    });
  }

  skipUpgrade(): void {
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_SKIP_UPGRADE, {});
  }
}
