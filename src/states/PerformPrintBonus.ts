/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformPrintBonus.ts
 *
 * Age of Comics perform print bonus state
 *
 * State vars:
 *  game: game object reference
 *  connections: click listener map
 *
 */
class PerformPrintBonus implements State {
  game: any;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = {};
  }

  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      this.createIdeaTokensFromSupplyActions();
    }
  }
  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");
    dojo.query(".aoc-selected").removeClass("aoc-selected");

    dojo.disconnect(this.connections["aoc-select-supply-idea-token-crime"]);
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-horror"]);
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-romance"]);
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-scifi"]);
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-superhero"]);
    dojo.disconnect(this.connections["aoc-select-supply-idea-token-western"]);
    dojo.disconnect(this.connections["aoc-idea-cancel-1"]);
    dojo.disconnect(this.connections["aoc-idea-cancel-2"]);

    this.connections = {};

    var selectionDiv = dojo.byId("aoc-idea-token-selection");
    if (selectionDiv) {
      selectionDiv.remove();
    }
  }
  onUpdateActionButtons(stateArgs: any): void {
    gameui.addActionButton("aoc-confirm-gain-ideas", _("Confirm"), () => {
      this.confirmGainIdeas();
    });
    dojo.addClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
    dojo.addClass("aoc-confirm-gain-ideas", "aoc-button");
  }

  /**
   * Called when the player confirms their idea selection.
   * Sends the selected ideas to the server.
   *
   */
  confirmGainIdeas(): void {
    // Get all selected ideas from supply
    var selectedIdeasFromSupply = dojo.query(".aoc-supply-idea-selection");

    // Initialize string to store idea ids in comma separated list
    var selectedIdeasFromSupplyGenres: string = "";

    // For each idea, add its id to the string
    for (var i = 0; i < selectedIdeasFromSupply.length; i++) {
      var idea = selectedIdeasFromSupply[i];
      if (i == 0) {
        selectedIdeasFromSupplyGenres += this.game.getGenreId(
          idea.id.split("-")[3]
        );
      } else {
        selectedIdeasFromSupplyGenres +=
          "," + this.game.getGenreId(idea.id.split("-")[3]);
      }
    }

    // Send the idea ids to the server
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_CONFIRM_GAIN_BONUS_IDEAS, {
      ideas: selectedIdeasFromSupplyGenres,
    });
  }

  /**
   * Creates a div to hold an idea selection.
   *
   * @param idNum the id number of the div
   */
  createIdeaSelectionDiv(idNum: number): void {
    // Create div for idea selection
    var ideaSelectionDiv =
      '<div id="aoc-supply-idea-selection-container-' +
      idNum +
      '" class="aoc-selection-container"><i id="aoc-idea-cancel-' +
      idNum +
      '" class="fa fa-lg fa-times-circle aoc-start-idea-remove aoc-hidden"></i></div>';

    // Add div to page
    this.game.createHtml(ideaSelectionDiv, "aoc-select-supply-idea-containers");

    // Add click listener to cancel button
    this.connections["aoc-idea-cancel-" + idNum] = dojo.connect(
      dojo.byId("aoc-idea-cancel-" + idNum),
      "onclick",
      dojo.hitch(this, "removeIdea", idNum)
    );
  }

  /**
   * Creates possible idea actions from supply.
   */
  createIdeaTokensFromSupplyActions(): void {
    // Create div for idea token selection
    var ideaTokenSelectionDiv =
      "<div id='aoc-idea-token-selection' class='aoc-action-panel-row'></div>";
    this.game.createHtml(ideaTokenSelectionDiv, "page-title");

    // Get all genres
    const genres = this.game.getGenres();

    // For each genre, create a button
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

    // Create divs for idea selection containers
    var selectionBoxesDiv =
      "<div id='aoc-select-supply-idea-containers' class='aoc-select-containers'></div>";
    this.game.createHtml(selectionBoxesDiv, "aoc-idea-token-selection");
    this.createIdeaSelectionDiv(1);
    this.createIdeaSelectionDiv(2);
  }

  /**
   * Gets the first empty idea selection div.
   *
   * @returns the first empty idea selection div or null if there are no empty idea selection divs
   */
  getFirstEmptyIdeaSelectionDiv(): any {
    // Get all idea selection divs
    var allDivs = dojo.query(".aoc-selection-container");

    // For each idea selection div, if it has no children, return it
    for (var i = 0; i < allDivs.length; i++) {
      var div = allDivs[i];
      if (div.children.length == 1) {
        return div;
      }
    }

    // If there are no empty idea selection divs, return null
    return null;
  }

  /**
   * Removes an idea from the idea selection div.
   *
   * @param slotId the id of the idea selection div
   */
  removeIdea(slotId: number): void {
    // Remove idea from selection div
    var ideaDiv = dojo.byId("aoc-selected-idea-box-" + slotId);
    ideaDiv.remove();

    // Hide cancel button
    dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", true);

    // Set status of confirmation button
    this.setButtonConfirmationStatus();
  }

  /**
   * Called when a player clicks on an idea on the supply.
   *
   * @param genre the genre of the idea
   */
  selectIdeaFromSupply(genre: string): void {
    // Get first empty idea selection div
    var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();

    // If there are no empty idea selection divs, return
    if (firstEmptySelectionDiv == null) {
      return;
    }

    // Get id of idea selection div
    var slotId = firstEmptySelectionDiv.id.split("-")[5];

    // Create div for selected idea
    var tokenDiv =
      '<div id="aoc-selected-idea-box-' +
      slotId +
      '"><div id="aoc-selected-idea-' +
      genre +
      '" class="aoc-supply-idea-selection aoc-idea-token aoc-idea-token-' +
      genre +
      '"></div></div>';

    // Add div to the idea selection div
    this.game.createHtml(tokenDiv, firstEmptySelectionDiv.id);

    // Unhide cancel button
    dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", false);

    // Set status of confirmation button
    this.setButtonConfirmationStatus();
  }

  /**
   * Sets the status of the confirmation button.
   * If all idea selection divs are full and all possible ideas from board are selected, enable the confirmation button.
   * If either are not true, disable the confirmation button.
   */
  setButtonConfirmationStatus(): void {
    var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();
    if (firstEmptySelectionDiv == null) {
      dojo.addClass("aoc-confirm-gain-ideas", "aoc-button");
      dojo.removeClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
    } else {
      dojo.addClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
      dojo.removeClass("aoc-confirm-gain-ideas", "aoc-button");
    }
  }
}
