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
 * State vars:
 *  game: game object reference
 *
 */

class PlayerSetup implements State {
  game: any;
  constructor(game: any) {
    this.game = game;
  }

  /**
   * Called when entering this state
   * Creates the starting items selection divs and events
   *
   * stateArgs:
   *  - isCurrentPlayerActive: true if this player is the active player
   *
   * args:
   * - startIdeas: number of starting ideas player can select
   *
   * @param stateArgs
   */
  onEnteringState(stateArgs: any): void {
    // Hide the card market
    dojo.toggleClass("aoc-card-market", "aoc-hidden", true);
    if (stateArgs.isCurrentPlayerActive) {
      // Show the starting items selection divs
      dojo.style("aoc-select-start-items", "display", "block");
      var startIdeas = stateArgs.args.startIdeas;

      // Create a selection div for each starting idea the player can select
      for (var i = 1; i <= startIdeas; i++) {
        this.createIdeaSelectionDiv(i);
      }
      // Create the click events for the starting items
      this.createOnClickEvents(startIdeas);
    }
    // Adapt the viewport size
    this.game.adaptViewportSize();
  }

  /**
   * Called when leaving this state
   * Removes the starting items selection divs and events
   *
   */
  onLeavingState(): void {
    // Hide the starting items selection divs
    dojo.style("aoc-select-start-items", "display", "none");
    // Remove the click events for the starting items
    dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
    dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");
    // Empty the starting items selection divs
    dojo.empty("aoc-select-containers");
    // Remove the click events for the starting items
    var genres = this.game.getGenres();
    for (var key in genres) {
      var genre = genres[key];

      var comicDivId = "aoc-select-starting-comic-" + genre;
      dojo.disconnect(dojo.byId(comicDivId));

      var ideaDivId = "aoc-select-starting-idea-" + genre;
      dojo.disconnect(dojo.byId(ideaDivId));
    }
    // Adapt the viewport size
    this.game.adaptViewportSize();
  }

  /**
   * Called when the action buttons are updated (start of the state)
   * Adds the confirm button
   *
   * stateArgs:
   *  - isCurrentPlayerActive: true if this player is the active player
   *
   * @param stateArgs
   */
  onUpdateActionButtons(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      // Add the confirm button
      gameui.addActionButton("aoc-confirm-starting-items", _("Confirm"), () => {
        this.confirmStartingItems();
      });
      // Disable the confirm button and add custom style
      dojo.addClass("aoc-confirm-starting-items", "aoc-button-disabled");
      dojo.addClass("aoc-confirm-starting-items", "aoc-button");
    }
  }

  /**
   * Called when the confirm button is clicked
   * Sends the selected starting items to the server
   */
  confirmStartingItems(): void {
    // Disable the confirm button
    dojo.addClass("aoc-confirm-starting-items", "aoc-button-disabled");

    // Get the selected comic genre
    var selectedComic = dojo.query(
      ".aoc-card-selected",
      "aoc-select-comic-genre"
    )[0];

    // Get the genre key
    var selectedComicGenre: number = this.game.getGenreId(
      selectedComic.id.split("-")[4]
    );

    // Get the selected idea genres
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

    // Send the selected starting items to the server
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_SELECT_START_ITEMS, {
      comic: selectedComicGenre,
      ideas: selectedIdeaGenres,
    });
  }

  /**
   * Creates the click events for the starting items
   *
   * @param startIdeas - number of starting ideas player can select
   */
  createOnClickEvents(startIdeas: number): void {
    // For each genre
    var genres = this.game.getGenres();
    for (var key in genres) {
      var genre = genres[key];

      // Create a comic div and click event
      var comicDivId = "aoc-select-starting-comic-" + genre;
      dojo.connect(
        dojo.byId(comicDivId),
        "onclick",
        dojo.hitch(this, "selectComic", genre)
      );

      // Create an idea div and click event
      var ideaDivId = "aoc-select-starting-idea-" + genre;
      dojo.connect(
        dojo.byId(ideaDivId),
        "onclick",
        dojo.hitch(this, "selectIdea", genre)
      );
    }

    // Create cancel click events for each starting idea container
    for (var i = 1; i <= startIdeas; i++) {
      var ideaCancelId = "aoc-idea-cancel-" + i;
      dojo.connect(
        dojo.byId(ideaCancelId),
        "onclick",
        dojo.hitch(this, "removeIdea", i)
      );
    }
  }

  /**
   * Creates a starting idea selection container
   *
   * @param idNum - the id number of the div
   */
  createIdeaSelectionDiv(idNum: number): void {
    var ideaSelectionDiv =
      '<div id="aoc-selection-container-' +
      idNum +
      '" class="aoc-selection-container"><i id="aoc-idea-cancel-' +
      idNum +
      '" class="fa fa-lg fa-times-circle aoc-start-idea-remove aoc-hidden"></i></div>';

    this.game.createHtml(ideaSelectionDiv, "aoc-select-containers");
  }

  /**
   * Gets the first empty starting idea selection div
   *
   * @returns the first empty starting idea selection div or null if none are empty
   */
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

  /**
   * Removes an idea from an idea selection container
   *
   * @param slotId - the id number of the container
   */
  removeIdea(slotId: number): void {
    var ideaDiv = dojo.byId("aoc-selected-idea-box-" + slotId);
    ideaDiv.remove();

    dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", true);

    // Set confirm button status
    this.setButtonConfirmationStatus();
  }

  /**
   * Selects a comic genre
   *
   * @param genre - the genre of the comic
   */
  selectComic(genre: string): void {
    // Remove the selected and unselected classes from all comics
    dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
    dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");

    // Add the selected class to the selected comic
    var divId = "aoc-select-starting-comic-" + genre;
    dojo.addClass(divId, "aoc-card-selected");

    // Add the unselected class to all other comics
    var allComics = dojo.byId("aoc-select-comic-genre").children;
    for (var i = 0; i < allComics.length; i++) {
      var comic = allComics[i];
      if (comic.id != divId) {
        dojo.toggleClass(comic.id, "aoc-card-unselected", true);
      }
    }

    // Set confirm button status
    this.setButtonConfirmationStatus();
  }

  /**
   * Selects an idea genre
   *
   * @param genre - the genre of the idea
   */
  selectIdea(genre: string): void {
    // Get the first empty starting idea selection div
    var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();

    // If there are no empty starting idea selection divs, return
    if (firstEmptySelectionDiv == null) {
      return;
    }

    // Get the id number of the empty starting idea selection div
    var slotId = firstEmptySelectionDiv.id.split("-")[3];

    // Create a matching idea token and add it to the empty starting idea selection div
    var tokenDiv =
      '<div id="aoc-selected-idea-box-' +
      slotId +
      '"><div id="aoc-selected-idea-' +
      genre +
      '" class="aoc-start-idea-selection aoc-idea-token aoc-idea-token-' +
      genre +
      '"></div></div>';

    this.game.createHtml(tokenDiv, firstEmptySelectionDiv.id);

    // Show the cancel button for the starting idea selection div
    dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", false);

    // Set confirm button status
    this.setButtonConfirmationStatus();
  }

  /**
   * Sets the confirmation button status
   * Disables the button if there are no empty starting idea selection divs or no selected comic
   */
  setButtonConfirmationStatus(): void {
    // Get the first empty starting idea selection div
    var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();

    // Get the selected comic
    var selectedComic = dojo.query(
      ".aoc-card-selected",
      "aoc-select-comic-genre"
    );

    // If there are no empty starting idea selection divs and there is a selected comic, enable the confirm button
    if (firstEmptySelectionDiv == null && selectedComic.length == 1) {
      dojo.toggleClass(
        "aoc-confirm-starting-items",
        "aoc-button-disabled",
        false
      );
    } else {
      // Otherwise, disable the confirm button
      dojo.toggleClass(
        "aoc-confirm-starting-items",
        "aoc-button-disabled",
        true
      );
    }
  }
}
