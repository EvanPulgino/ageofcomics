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
 * AgeOfComics perform ideas state
 *
 * State vars:
 * - game: game object reference
 * - shouldUnselect: true if ideas should be unselected when the number of selected ideas is less than the number of ideas to gain
 * - ideasFromBoard: number of ideas to gain from the board
 * - connections: map of idea id to click listener
 *
 */

class PerformIdeas implements State {
  game: any;
  shouldUnselect: boolean;
  ideasFromBoard: number;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.shouldUnselect = false;
    this.ideasFromBoard = 0;
    this.connections = {};
  }

  /**
   * Called when entering this state.
   * Creates possible idea actions.
   *
   * stateArgs:
   * - isCurrentPlayerActive: true if this player is the active player
   *
   * args:
   * - ideasFromBoard: number of ideas to gain from the board
   *
   * @param stateArgs
   */
  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      const ideasFromBoard = stateArgs.args.ideasFromBoard;
      this.ideasFromBoard = ideasFromBoard;

      this.createIdeaTokensFromSupplyActions();
      this.createIdeaTokensOnBoardActions(ideasFromBoard);
    }
  }

  /**
   * Called when leaving this state.
   * Removes click listeners from cards.
   * Removes idea selection div.
   */
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

    var selectionDiv = dojo.byId("aoc-idea-token-selection");
    if (selectionDiv) {
      selectionDiv.remove();
    }
  }

  /**
   * Called when entering state to update buttons.
   *
   * stateArgs:
   * - isCurrentPlayerActive: true if this player is the active player
   *
   * Add a confirmation button that starts disabled.
   *
   * @param stateArgs
   */
  onUpdateActionButtons(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      gameui.addActionButton("aoc-confirm-gain-ideas", _("Confirm"), () => {
        this.confirmGainIdeas();
      });
      dojo.addClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
      dojo.addClass("aoc-confirm-gain-ideas", "aoc-button");
    }
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

    // Get all selected ideas from board
    var selectedIdeasFromBoard = dojo.query(".aoc-selected");

    // Initialize string to store idea ids in comma separated list
    var selectedIdeasFromBoardGenres: string = "";

    // For each idea, add its id to the string
    for (var i = 0; i < selectedIdeasFromBoard.length; i++) {
      var idea = selectedIdeasFromBoard[i];
      if (i == 0) {
        selectedIdeasFromBoardGenres += this.game.getGenreId(
          idea.id.split("-")[3]
        );
      } else {
        selectedIdeasFromBoardGenres +=
          "," + this.game.getGenreId(idea.id.split("-")[3]);
      }
    }

    // Send the idea ids to the server
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_CONFIRM_GAIN_IDEAS, {
      ideasFromBoard: selectedIdeasFromBoardGenres,
      ideasFromSupply: selectedIdeasFromSupplyGenres,
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
   * Creates possible idea actions from board.
   *
   * @param ideasFromBoard the number of ideas to gain from the board
   */
  createIdeaTokensOnBoardActions(ideasFromBoard: number): void {
    // If there are ideas to gain from the board, make all idea spaces clickable and add click listeners
    if (ideasFromBoard > 0) {
      // Get all idea spaces
      const ideaSpaces = dojo.byId("aoc-action-ideas-idea-spaces").children;

      // For each idea space, make it clickable and add a click listener
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
   * Called when a player clicks on an idea on the board.
   *
   * @param divId the id of the idea div
   * @param ideasFromBoard the number of ideas to gain from the board
   */
  selectIdeaFromBoard(divId: string, ideasFromBoard: number): void {
    // Toggle selected and clickable status
    dojo.byId(divId).classList.toggle("aoc-selected");
    dojo.byId(divId).classList.toggle("aoc-clickable");

    // Get all selected ideas
    var selectedIdeas = dojo.query(".aoc-selected");

    // If the number of selected ideas is equal to the number of ideas to gain from the board,
    // make all unselected ideas unclickable and disconnect their click listeners
    if (selectedIdeas.length == ideasFromBoard) {
      var unselectedIdeas = dojo.query(".aoc-clickable");
      for (var i = 0; i < unselectedIdeas.length; i++) {
        var unselectedIdea = unselectedIdeas[i];
        dojo.byId(unselectedIdea.id).classList.toggle("aoc-clickable");
        dojo.disconnect(this.connections[unselectedIdea.id]);
      }
      // Set shouldUnselect to true to indicate that ideas should be unselected when the number
      // of selected ideas is less than the number of ideas to gain from the board
      this.shouldUnselect = true;
    }

    // If the number of selected ideas is less than the number of ideas to gain from the board
    // and shouldUnselect is true, make all unselected ideas clickable and add click listeners
    else if (selectedIdeas.length < ideasFromBoard && this.shouldUnselect) {
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
      // Set shouldUnselect to false to indicate that ideas should not be unselected when the number
      // of selected ideas is less than the number of ideas to gain from the board
      this.shouldUnselect = false;
    }

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
    var selectedIdeasFromBoard = dojo.query(".aoc-selected");
    if (
      firstEmptySelectionDiv == null &&
      selectedIdeasFromBoard.length == this.ideasFromBoard
    ) {
      dojo.addClass("aoc-confirm-gain-ideas", "aoc-button");
      dojo.removeClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
    } else {
      dojo.addClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
      dojo.removeClass("aoc-confirm-gain-ideas", "aoc-button");
    }
  }
}
