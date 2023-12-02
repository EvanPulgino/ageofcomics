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
      var genreSpace = ideasSpaceContents[key];
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
    if (exists == 1) {
      var genre = this.ui.getGenreName(genreId);
      var ideaTokenDiv =
        '<div id="aoc-idea-token-' +
        genre +
        '" class="aoc-idea-token aoc-idea-token-' +
        genre +
        '"></div>';
      this.ui.createHtml(ideaTokenDiv, "aoc-action-ideas-" + genre);
    }
  }
}
