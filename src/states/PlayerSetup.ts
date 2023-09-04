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
  game: any;
  constructor(game: any) {
    this.game = game;
  }

  onEnteringState(stateArgs: any): void {
    dojo.toggleClass("aoc-card-market", "aoc-hidden", true);
    if (stateArgs.isCurrentPlayerActive) {
      dojo.style("aoc-select-start-items", "display", "block");
      var startIdeas = stateArgs.args.startIdeas;
      console.log("startIdeas: " + startIdeas);

      for (var i = 1; i <= startIdeas; i++) {
        this.createIdeaSelectionDiv(i);
      }
      this.createOnClickEvents(startIdeas);
    }
    this.game.adaptViewportSize();
  }
  onLeavingState(): void {
    dojo.style("aoc-select-start-items", "display", "none");
    dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
    dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");
    dojo.empty("aoc-select-containers");
    this.game.adaptViewportSize();
  }
  onUpdateActionButtons(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      gameui.addActionButton(
        "aoc-confirm-starting-items",
        _("Confirm"),
        (event) => {
          this.confirmStartingItems(event);
        }
      );
      dojo.addClass("aoc-confirm-starting-items", "aoc-button-disabled");
      dojo.addClass("aoc-confirm-starting-items", "aoc-button");
    }
  }

  confirmStartingItems(event): void {
    var selectedComic = dojo.query(
      ".aoc-card-selected",
      "aoc-select-comic-genre"
    )[0];

    var selectedComicGenre: number = this.game.getGenreId(
      selectedComic.id.split("-")[4]
    );

    var selectedIdeas = dojo.query(".aoc-start-idea-selection");
    var selectedIdeaGenres: string = "";
    for (var i = 0; i < selectedIdeas.length; i++) {
      var idea = selectedIdeas[i];
      if (i == 0) {
        selectedIdeaGenres += this.game.getGenreId(idea.id.split("-")[3]);
      } else {
        selectedIdeaGenres += ",";
        selectedIdeaGenres += this.game.getGenreId(idea.id.split("-")[3]);
      }
    }

    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_SELECT_START_ITEMS, {
      comic: selectedComicGenre,
      ideas: selectedIdeaGenres,
    });
  }

  createOnClickEvents(startIdeas): void {
    var genres = this.game.getGenres();
    for (var key in genres) {
      var genre = genres[key];

      var comicDivId = "aoc-select-starting-comic-" + genre;
      dojo.connect(
        dojo.byId(comicDivId),
        "onclick",
        dojo.hitch(this, "selectComic", genre)
      );

      var ideaDivId = "aoc-select-starting-idea-" + genre;
      dojo.connect(
        dojo.byId(ideaDivId),
        "onclick",
        dojo.hitch(this, "selectIdea", genre)
      );
    }

    for (var i = 1; i <= startIdeas; i++) {
      var ideaCancelId = "aoc-idea-cancel-" + i;
      dojo.connect(
        dojo.byId(ideaCancelId),
        "onclick",
        dojo.hitch(this, "removeIdea", i)
      );
    }
  }

  createIdeaSelectionDiv(idNum: number): void {
    var ideaSelectionDiv =
      '<div id="aoc-selection-container-' +
      idNum +
      '" class="aoc-selection-container"><i id="aoc-idea-cancel-' +
      idNum +
      '" class="fa fa-lg fa-times-circle aoc-start-idea-remove aoc-hidden"></i></div>';

    this.game.createHtml(ideaSelectionDiv, "aoc-select-containers");
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

    this.setButtonConfirmationStatus();
  }

  selectComic(genre: string): void {
    dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
    dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");

    var divId = "aoc-select-starting-comic-" + genre;
    dojo.addClass(divId, "aoc-card-selected");

    var allComics = dojo.byId("aoc-select-comic-genre").children;
    for (var i = 0; i < allComics.length; i++) {
      var comic = allComics[i];
      if (comic.id != divId) {
        dojo.toggleClass(comic.id, "aoc-card-unselected", true);
      }
    }

    this.setButtonConfirmationStatus();
  }

  selectIdea(genre: string): void {
    var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();

    if (firstEmptySelectionDiv == null) {
      return;
    }

    var slotId = firstEmptySelectionDiv.id.split("-")[3];

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

    this.setButtonConfirmationStatus();
  }

  setButtonConfirmationStatus(): void {
    var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();
    var selectedComic = dojo.query(
      ".aoc-card-selected",
      "aoc-select-comic-genre"
    );
    if (firstEmptySelectionDiv == null && selectedComic.length == 1) {
      dojo.toggleClass(
        "aoc-confirm-starting-items",
        "aoc-button-disabled",
        false
      );
    } else {
      dojo.toggleClass(
        "aoc-confirm-starting-items",
        "aoc-button-disabled",
        true
      );
    }
  }
}
