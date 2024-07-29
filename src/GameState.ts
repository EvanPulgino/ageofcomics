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
  endStartNewRound: State;
  enterIncreaseCreatives: State;
  gameEnd: State;
  gameSetup: State;
  increaseCreatives: State;
  nextPlayer: State;
  nextPlayerSetup: State;
  performDevelop: State;
  performHire: State;
  performIdeas: State;
  performPrint: State;
  performPrintBonus: State;
  performPrintContinue: State;
  performPrintGetUpgradeCube: State;
  performPrintMastery: State;
  performPrintUpgrade: State;
  performReassign: State;
  performRoyalties: State;
  performSales: State;
  performSalesContinue: State;
  performSalesFulfillOrder: State;
  playerSetup: State;
  playerTurn: State;
  roundEndEstablishPlayerOrder: State;
  roundEndEstablishRanking: State;
  roundEndPayEarnings: State;
  roundEndRefillCards: State;
  roundEndRemoveEditors: State;
  roundEndSubtractFans: State;
  startNewRound: State;
  constructor(game: any) {
    this.checkHandSize = new CheckHandSize(game);
    this.completeSetup = new CompleteSetup(game);
    this.endStartNewRound = new EndStartNewRound(game);
    this.enterIncreaseCreatives = new EnterIncreaseCreatives(game);
    this.gameEnd = new GameEnd(game);
    this.gameSetup = new GameSetup(game);
    this.increaseCreatives = new IncreaseCreatives(game);
    this.nextPlayer = new NextPlayer(game);
    this.nextPlayerSetup = new NextPlayerSetup(game);
    this.performDevelop = new PerformDevelop(game);
    this.performHire = new PerformHire(game);
    this.performIdeas = new PerformIdeas(game);
    this.performPrint = new PerformPrint(game);
    this.performPrintBonus = new PerformPrintBonus(game);
    this.performPrintContinue = new PerformPrintContinue(game);
    this.performPrintGetUpgradeCube = new PerformPrintGetUpgradeCube(game);
    this.performPrintMastery = new PerformPrintMastery(game);
    this.performPrintUpgrade = new PerformPrintUpgrade(game);
    this.performReassign = new PerformReassign(game);
    this.performRoyalties = new PerformRoyalties(game);
    this.performSales = new PerformSales(game);
    this.performSalesContinue = new PerformSalesContinue(game);
    this.performSalesFulfillOrder = new PerformSalesFulfillOrder(game);
    this.playerSetup = new PlayerSetup(game);
    this.playerTurn = new PlayerTurn(game);
    this.roundEndEstablishPlayerOrder = new RoundEndEstablishPlayerOrder(game);
    this.roundEndEstablishRanking = new RoundEndEstablishRanking(game);
    this.roundEndPayEarnings = new RoundEndPayEarnings(game);
    this.roundEndRefillCards = new RoundEndRefillCards(game);
    this.roundEndRemoveEditors = new RoundEndRemoveEditors(game);
    this.roundEndSubtractFans = new RoundEndSubtractFans(game);
    this.startNewRound = new StartNewRound(game);
  }
}
