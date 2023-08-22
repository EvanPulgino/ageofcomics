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
      dojo.style("aoc-select-start-items", "display", "block");
      var startIdeas = stateArgs.args.startIdeas;

      for (var i = 1; i <= startIdeas; i++) {
        this.createIdeaSelectionDiv(game, i);
      }
      this.createOnClickEvents(game.getGenres());
    }
  }
  onLeavingState(game: any): void {
    dojo.style("aoc-select-start-items", "display", "none");
    dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
    dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");
  }
  onUpdateActionButtons(game: any, stateArgs: any): void {}

  createOnClickEvents(genres: any): void {
    for (var key in genres) {
      var genre = genres[key];
      var divId = "aoc-select-starting-" + genre;
      dojo.connect(
        dojo.byId(divId),
        "onclick",
        dojo.hitch(this, "selectComic", genre)
      );
    }
  }

  createIdeaSelectionDiv(game: any, idNum: number): void {
    var ideaSelectionDiv =
      '<div id="aoc-selection-container-' +
      idNum +
      '" class="aoc-selection-container"></div>';

    game.createHtml(ideaSelectionDiv, "aoc-select-containers");
  }

  selectComic(genre: string): void {
    dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
    dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");

    var divId = "aoc-select-starting-" + genre;
    dojo.addClass(divId, "aoc-card-selected");

    var allComics = dojo.byId("aoc-select-comic-genre").children;
    for (var i = 0; i < allComics.length; i++) {
      var comic = allComics[i];
      if (comic.id != divId) {
        dojo.addClass(comic.id, "aoc-card-unselected");
      }
    }
  }
}
