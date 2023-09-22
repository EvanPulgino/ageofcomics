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
  completeSetup: State;
  gameEnd: State;
  gameSetup: State;
  nextPlayer: State;
  nextPlayerSetup: State;
  playerSetup: State;
  playerTurn: State;
  startNewRound: State;
  constructor(game: any) {
    this.completeSetup = new CompleteSetup(game);
    this.gameEnd = new GameEnd(game);
    this.gameSetup = new GameSetup(game);
    this.nextPlayer = new NextPlayer(game);
    this.nextPlayerSetup = new NextPlayerSetup(game);
    this.playerSetup = new PlayerSetup(game);
    this.playerTurn = new PlayerTurn(game);
    this.startNewRound = new StartNewRound(game);
  }
}
