/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * GameState.ts
 *
 */

class GameState {
  gameEnd: State;
  gameSetup: State;
  playerSetup: State;
  constructor() {
    this.gameEnd = new GameEnd();
    this.gameSetup = new GameSetup();
    this.playerSetup = new PlayerSetup();
  }
}
