/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * CompleteSetup.ts
 *
 * AgeOfComics complete setup state
 *
 * State vars:
 * - game: game object reference
 *
 */
class CompleteSetup implements State {
  game: any;
  constructor(game: any) {
    this.game = game;
  }

  /**
   * Called when entering this state.
   * Upon entering this state, the game is ready to play.
   *
   * stateArgs:
   *  - isCurrentPlayerActive: true if this player is the active player
   *
   * @param stateArgs contains args derived from the state machine
   */
  onEnteringState(stateArgs: any): void {
    // Make the card market visible and adapt the viewport size
    dojo.toggleClass("aoc-card-market", "aoc-hidden", false);

    // Adapt the viewport size
    this.game.adaptViewportSize();
  }
  onLeavingState(): void {}
  onUpdateActionButtons(stateArgs: any): void {}
}
