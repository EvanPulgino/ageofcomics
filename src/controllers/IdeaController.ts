/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * IdeaController.ts
 *
 * Handles idea token logic on front-end
 *
 */

class IdeaController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  /**
   * Set up idea tokens
   * @param {object} ideaSpaceContents - current idea token data used to initialize UI
   */
  setupIdeas(ideaSpaceContents: any) {
    this.createIdeaTokensOnBoard(ideaSpaceContents);
  }

  /**
   * Creates the idea tokens on the board
   * @param {object} ideasSpaceContents - ideas space contents
   */
  createIdeaTokensOnBoard(ideasSpaceContents: any) {
    for (var key in ideasSpaceContents) {
      const genreSpace = ideasSpaceContents[key];
      this.createIdeaTokenOnBoard(key, genreSpace);
    }
  }

  /**
   * Creates an idea token on the board
   *
   * @param genreId - the genre id of the idea token
   * @param exists - whether or not the idea token exists on the board
   */
  createIdeaTokenOnBoard(genreId: any, exists: number) {
    const genre = this.ui.getGenreName(genreId);
    const ideaContainer = dojo.byId("aoc-action-ideas-" + genre);
    if (exists == 1 && ideaContainer.childElementCount == 0) {
      var ideaTokenDiv =
        '<div id="aoc-idea-token-' +
        genre +
        '" class="aoc-idea-token aoc-idea-token-' +
        genre +
        '"></div>';
      this.ui.createHtml(ideaTokenDiv, "aoc-action-ideas-" + genre);
    }
  }

  /**
   * Creates an idea token on a card
   *
   * @param genre - the genre of the idea token
   * @param cardId - the card id of the card the idea token is on
   */
  createIdeaTokenOnCard(genre: string, cardId: any): any {
    var randomId = Math.floor(Math.random() * 1000000);
    var ideaTokenDiv =
      '<div id="' +
      randomId +
      '" class="aoc-idea-token aoc-idea-token-' +
      genre +
      '" style="position:relative;z-index:1000;"></div>';
    return this.ui.createHtml(ideaTokenDiv, "aoc-card-" + cardId);
  }

  /**
   * Creates an idea token on the supply
   *
   * @param genre - the genre of the idea token
   */
  createIdeaTokenOnSupply(genre: string): any {
    var randomId = Math.floor(Math.random() * 1000000);
    var ideaTokenDiv =
      '<div id="' +
      randomId +
      '" class="aoc-idea-token aoc-idea-token-' +
      genre +
      '" style="position:relative;z-index:1000;"></div>';
    return this.ui.createHtml(
      ideaTokenDiv,
      "aoc-select-supply-idea-token-" + genre
    );
  }

  /**
   * Creates an idea token on the select start ideas container
   *
   * @param genre - the genre of the idea token
   */
  createStartingIdeaToken(genre: string): any {
    var randomId = Math.floor(Math.random() * 1000000);
    var ideaTokenDiv =
      '<div id="' +
      randomId +
      '" class="aoc-idea-token aoc-idea-token-' +
      genre +
      '" style="position:relative;z-index:1000;"></div>';
    return this.ui.createHtml(
      ideaTokenDiv,
      "aoc-select-starting-idea-" + genre
    );
  }

  /**
   * Moves an idea token from the board to a player's panel
   *
   * @param playerId - the player id of the player who gained the idea token
   * @param genre - the genre of the idea token
   */
  gainIdeaFromBoard(playerId: any, genre: string): void {
    var ideaTokenDiv = dojo.byId("aoc-idea-token-" + genre);
    var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
    gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
  }

  /**
   * Create an idea token on a card and move it to a player's panel
   *
   * @param playerId - the player id of the player who gained the idea token
   * @param genre - the genre of the idea token
   * @param cardId - the card id of the card the idea token is on
   */
  gainIdeaFromHiringCreative(playerId: any, genre: string, cardId: any) {
    var ideaTokenDiv = this.createIdeaTokenOnCard(genre, cardId);
    var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
    gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
  }

  /**
   * Create an idea token on the supply and move it to a player's panel
   *
   * @param playerId - the player id of the player who gained the idea token
   * @param genre - the genre of the idea token
   */
  gainIdeaFromSupply(playerId: any, genre: string): void {
    var ideaTokenDiv = this.createIdeaTokenOnSupply(genre);
    var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
    gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
  }

  /**
   * Create an idea token on the select start ideas container and move it to a player's panel
   *
   * @param playerId - the player id of the player who gained the idea token
   * @param genre - the genre of the idea token
   */
  gainStartingIdea(playerId: any, genre: string): void {
    var ideaTokenDiv = this.createStartingIdeaToken(genre);
    var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
    gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
  }
}
