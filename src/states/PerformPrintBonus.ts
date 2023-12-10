/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformPrintBonus.ts
 *
 * Age of Comics perform print bonus state
 *
 * State vars:
 *  game: game object reference
 *  connections: click listener map
 *
 */
class PerformPrintBonus implements State {
  game: any;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = {};
  }

  onEnteringState(stateArgs: any): void {
    console.log("Entering PerformPrintBonus");
  }
  onLeavingState(): void {}
  onUpdateActionButtons(stateArgs: any): void {}
}
