/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PlayerSetup.ts
 *
 * AgeOfComics player setup state
 *
 */

class PlayerSetup implements State {
  onEnteringState(game: any, stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
    }
  }
  onLeavingState(game: any): void {}
  onUpdateActionButtons(game: any, stateArgs: any): void {}

}
