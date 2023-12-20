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
 * AgeOfComics perform print mastery state
 *
 * State vars:
 * - game: game object reference
 *
 */
class PerformPrintMastery implements State {
  game: any;
  constructor(game: any) {
    this.game = game;
  }

  onEnteringState(stateArgs: any): void {}
  onLeavingState(): void {}
  onUpdateActionButtons(stateArgs: any): void {}
}
