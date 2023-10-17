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

    this.createIdeaTokensFromSupplyActions();
    this.createIdeaTokensOnBoardActions(ideasFromBoard);
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
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-crime"]);
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-horror"]);
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-romance"]);
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-scifi"]);
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-superhero"]);
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-western"]);
    dojo.disconnect(this.connections["aoc-idea-cancel-1"]);
    dojo.disconnect(this.connections["aoc-idea-cancel-2"]);

    this.connections = {};

    dojo.byId("aoc-idea-token-selection").remove();
  }
  onUpdateActionButtons(stateArgs: any): void {}

  createIdeaSelectionDiv(idNum: number): void {
    var ideaSelectionDiv =
      '<div id="aoc-supply-idea-selection-container-' +
      idNum +
      '" class="aoc-selection-container"><i id="aoc-idea-cancel-' +
      idNum +
      '" class="fa fa-lg fa-times-circle aoc-start-idea-remove aoc-hidden"></i></div>';

    this.game.createHtml(
      ideaSelectionDiv,
      "aoc-select-supply-ideas-containers"
    );

    this.connections["aoc-idea-cancel-" + idNum] = dojo.connect(
      dojo.byId("aoc-idea-cancel-" + idNum),
      "onclick",
      dojo.hitch(this, "removeIdea", idNum)
    );
  }

  createIdeaTokensFromSupplyActions(): void {
    var ideaTokenSelectionDiv = "<div id='aoc-idea-token-selection'></div>";
    this.game.createHtml(ideaTokenSelectionDiv, "page-title");

    const genres = this.game.getGenres();
    for (var key in genres) {
      var genre = genres[key];
      var ideaTokenDiv =
        "<div id='aoc-select-supply-idea-token-" +
        genre +
        "' class='aoc-idea-token aoc-idea-token-" +
        genre +
        "'></div>";
      this.game.createHtml(ideaTokenDiv, "aoc-idea-token-selection");

      this.connections["aoc-select-supply-idea-token-" + genre] = dojo.connect(
        dojo.byId("aoc-select-supply-idea-token-" + genre),
        "onclick",
        dojo.hitch(this, "selectIdeaFromSupply", genre)
      );
    }

    var selectionBoxesDiv =
      "<div id='aoc-select-supply-ideas-containers'></div>";
    this.game.createHtml(selectionBoxesDiv, "aoc-idea-token-selection");
    this.createIdeaSelectionDiv(1);
    this.createIdeaSelectionDiv(2);
  }

  createIdeaTokensOnBoardActions(ideasFromBoard: number): void {
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

  getFirstEmptyIdeaSelectionDiv(): any {
    var allDivs = dojo.query(".aoc-selection-container");
    for (var i = 0; i < allDivs.length; i++) {
      var div = allDivs[i];
      if (div.children.length == 1) {
        return div;
      }
    }
    return null;
  }

  removeIdea(slotId: number): void {
    var ideaDiv = dojo.byId("aoc-selected-idea-box-" + slotId);
    ideaDiv.remove();

    dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", true);
  }

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
          dojo.hitch(
            this,
            "selectIdeaFromBoard",
            ideaToActivate.id,
            ideasFromBoard
          )
        );
      }
    }
  }

  selectIdeaFromSupply(genre: string): void {
    var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();

    if (firstEmptySelectionDiv == null) {
      return;
    }

    var slotId = firstEmptySelectionDiv.id.split("-")[5];

    var tokenDiv =
      '<div id="aoc-selected-idea-box-' +
      slotId +
      '"><div id="aoc-selected-idea-' +
      genre +
      '" class="aoc-start-idea-selection aoc-idea-token aoc-idea-token-' +
      genre +
      '"></div></div>';

    this.game.createHtml(tokenDiv, firstEmptySelectionDiv.id);

    dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", false);
  }
}
