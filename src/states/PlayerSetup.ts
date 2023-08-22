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
    }
  }
  onLeavingState(game: any): void {
    dojo.style("aoc-select-start-items", "display", "none");
  }
  onUpdateActionButtons(game: any, stateArgs: any): void {}

  createIdeaSelectionDiv(game: any, idNum: number): void {
    var ideaSelectionDiv =
      '<div id="aoc-selection-container-' +
      idNum +
      '" class="aoc-selection-container"></div>';

    game.createHtml(ideaSelectionDiv, "aoc-select-containers");
  }
}
