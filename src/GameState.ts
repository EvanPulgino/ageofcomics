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
  checkHandSize: State;
  completeSetup: State;
  gameEnd: State;
  gameSetup: State;
  nextPlayer: State;
  nextPlayerSetup: State;
  performDevelop: State;
  performHire: State;
  performIdeas: State;
  performPrint: State;
  performPrintBonus: State;
  performPrintFulfillOrders: State;
  performPrintMastery: State;
  performPrintUpgrade: State;
  performRoyalties: State;
  performSales: State;
  playerSetup: State;
  playerTurn: State;
  startNewRound: State;
  constructor(game: any) {
    this.checkHandSize = new CheckHandSize(game);
    this.completeSetup = new CompleteSetup(game);
    this.gameEnd = new GameEnd(game);
    this.gameSetup = new GameSetup(game);
    this.nextPlayer = new NextPlayer(game);
    this.nextPlayerSetup = new NextPlayerSetup(game);
    this.performDevelop = new PerformDevelop(game);
    this.performHire = new PerformHire(game);
    this.performIdeas = new PerformIdeas(game);
    this.performPrint = new PerformPrint(game);
    this.performPrintBonus = new PerformPrintBonus(game);
    this.performPrintFulfillOrders = new PerformPrintFulfillOrders(game);
    this.performPrintMastery = new PerformPrintMastery(game);
    this.performPrintUpgrade = new PerformPrintUpgrade(game);
    this.performRoyalties = new PerformRoyalties(game);
    this.performSales = new PerformSales(game);
    this.playerSetup = new PlayerSetup(game);
    this.playerTurn = new PlayerTurn(game);
    this.startNewRound = new StartNewRound(game);
  }
}
