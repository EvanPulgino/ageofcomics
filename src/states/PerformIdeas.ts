/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformIdeas.ts
 *
 */

class PerformIdeas implements State {
  game: any;
  unselect: boolean;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.unselect = false;
    this.connections = {};
  }

  onEnteringState(stateArgs: any): void {
    const ideasFromBoard = stateArgs.args.ideasFromBoard;

    if (ideasFromBoard > 0) {
      const ideaSpaces = dojo.byId("aoc-action-ideas-idea-spaces").children;
      for (var key in ideaSpaces) {
        var ideaSpace = ideaSpaces[key];
        if (
          dojo.hasClass(ideaSpace, "aoc-action-ideas-idea-space") &&
          ideaSpace.children.length > 0
        ) {
          var ideaTokenDiv = ideaSpace.children[0];
          dojo.addClass(ideaTokenDiv, "aoc-clickable");
          this.connections[ideaTokenDiv.id] = dojo.connect(
            dojo.byId(ideaTokenDiv.id),
            "onclick",
            dojo.hitch(
              this,
              "selectIdeaFromBoard",
              ideaTokenDiv.id,
              ideasFromBoard
            )
          );
        }
      }
    }
  }
  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");
    dojo.query(".aoc-selected").removeClass("aoc-selected");

    dojo.disconnect(this.connections["aoc-idea-token-crime"]);
    dojo.disconnect(this.connections["aoc-idea-token-horror"]);
    dojo.disconnect(this.connections["aoc-idea-token-romance"]);
    dojo.disconnect(this.connections["aoc-idea-token-scifi"]);
    dojo.disconnect(this.connections["aoc-idea-token-superhero"]);
    dojo.disconnect(this.connections["aoc-idea-token-western"]);

    this.connections = {};
  }
  onUpdateActionButtons(stateArgs: any): void {}

  selectIdeaFromBoard(divId: string, ideasFromBoard: number): void {
    dojo.byId(divId).classList.toggle("aoc-selected");
    dojo.byId(divId).classList.toggle("aoc-clickable");

    var selectedIdeas = dojo.query(".aoc-selected");
    if (selectedIdeas.length == ideasFromBoard) {
      var unselectedIdeas = dojo.query(".aoc-clickable");
      for (var i = 0; i < unselectedIdeas.length; i++) {
        var unselectedIdea = unselectedIdeas[i];
        dojo.byId(unselectedIdea.id).classList.toggle("aoc-clickable");
        dojo.disconnect(this.connections[unselectedIdea.id]);
      }
      this.unselect = true;
    }
    if (selectedIdeas.length < ideasFromBoard && this.unselect) {
      var ideasToActivate = dojo.query(
        ".aoc-action-ideas-idea-space > .aoc-idea-token:not(.aoc-selected):not(.aoc-clickable)"
      );
      for (var i = 0; i < ideasToActivate.length; i++) {
        var ideaToActivate = ideasToActivate[i];
        dojo.byId(ideaToActivate.id).classList.toggle("aoc-clickable");
        this.connections[ideaToActivate.id] = dojo.connect(
          dojo.byId(ideaToActivate.id),
          "onclick",
          dojo.hitch(this, "selectIdeaFromBoard", ideaToActivate.id, ideasFromBoard)
        );
      }
    }
  }
}
