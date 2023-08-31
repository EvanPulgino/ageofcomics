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
 * Class that holds all game states
 *
 */

class GameState {
  gameEnd: State;
  gameSetup: State;
  playerSetup: State;
  constructor(game: any) {
    this.gameEnd = new GameEnd(game);
    this.gameSetup = new GameSetup(game);
    this.playerSetup = new PlayerSetup(game);
  }
}
