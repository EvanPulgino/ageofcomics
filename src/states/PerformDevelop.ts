/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformDevelop.ts
 *
 * AgeOfComics perform develop state
 *
 * State vars:
 * - game: game object reference
 * - connections: object containing dojo connections
 *
 */
class PerformDevelop implements State {
  game: any;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = {};
  }

  /**
   * Called when entering this state
   *
   * Creates possible develop actions
   *
   * stateArgs:
   * - isCurrentPlayerActive: true if this player is the active player
   *
   * args:
   * - availableGenres: the genres that the player can develop from the deck (aka those that have at least one card in the deck or discard)
   * - canDevelopFromDeck: true if the player can develop from the deck
   *
   * @param stateArgs
   */
  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      this.createDevelopActions();
      if (stateArgs.args.canDevelopFromDeck) {
        this.createDevelopFromDeckActions(stateArgs.args.availableGenres);
      }
    }
  }

  /**
   * Called when leaving this state
   *
   * Removes click listeners from cards
   */
  onLeavingState(): void {
    // Remove click listeners from cards
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");

    // Remove all click listeners
    for (var key in this.connections) {
      dojo.disconnect(this.connections[key]);
    }

    // Clear connections object
    this.connections = {};

    // Remove develop from deck buttons
    var buttonRowDiv = dojo.byId("aoc-develop-from-deck-buttons");
    if (buttonRowDiv) {
      buttonRowDiv.remove();
    }
  }

  onUpdateActionButtons(stateArgs: any): void {}

  /**
   * Creates develop actions for the player
   */
  createDevelopActions(): void {
    // Make the top card of the comic deck clickable and add a click listener
    var topCardOfDeck = dojo.byId("aoc-comic-deck").lastChild;
    topCardOfDeck.classList.add("aoc-clickable");
    var topCardOfDeckId = topCardOfDeck.id.split("-")[2];
    this.connections["comic" + topCardOfDeckId] = dojo.connect(
      dojo.byId(topCardOfDeck.id),
      "onclick",
      dojo.hitch(this, this.developComic, topCardOfDeckId, true)
    );

    // Get all cards in comic market
    var cardElements = dojo.byId("aoc-comics-available").children;

    // Make all cards in comic market clickable and add click listeners
    for (var key in cardElements) {
      var card = cardElements[key];
      if (card.id) {
        card.classList.add("aoc-clickable");
        var cardId = card.id.split("-")[2];
        this.connections["comic" + cardId] = dojo.connect(
          dojo.byId(card.id),
          "onclick",
          dojo.hitch(this, this.developComic, cardId, false)
        );
      }
    }
  }

  /**
   * Creates develop from deck actions for the player
   *
   * @param availableGenres the genres that the player can develop from the deck (aka those that have at least one card in the deck or discard)
   */
  createDevelopFromDeckActions(availableGenres: any): void {
    // Create div for develop from deck buttons
    var buttonRowDiv =
      "<div id='aoc-develop-from-deck-buttons' class='aoc-action-panel-row'><div id='aoc-seach-icon' class='aoc-search-icon'></div></div>";
    this.game.createHtml(buttonRowDiv, "page-title");

    // Get all genres
    const genres = this.game.getGenres();

    // For each genre, create a button
    for (var key in genres) {
      var genre = genres[key];
      var buttonDiv =
        "<div id='aoc-develop-from-deck-" +
        genre +
        "' class='aoc-mini-comic-card aoc-mini-comic-card-" +
        genre +
        "'></div>";
      this.game.createHtml(buttonDiv, "aoc-develop-from-deck-buttons");

      // If the player can develop from the deck, make the button clickable and add a click listener
      if (availableGenres[genre] > 0) {
        dojo.addClass("aoc-develop-from-deck-" + genre, "aoc-image-clickable");
        this.connections["developFromDeck" + genre] = dojo.connect(
          dojo.byId("aoc-develop-from-deck-" + genre),
          "onclick",
          dojo.hitch(this, this.developComicFromDeck, genre)
        );
      } else {
        // If the player cannot develop from the deck, make the button disabled
        dojo.addClass("aoc-develop-from-deck-" + genre, "aoc-image-disabled");
      }
    }
  }

  /**
   * Called when a player clicks on a comic card
   *
   * @param comicId the id of the comic card
   * @param topOfDeck true if the card is the top card of the deck
   */
  developComic(comicId: number, topOfDeck: boolean): void {
    // Call the develop comic action for the comic
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_DEVELOP_COMIC, {
      comicId: comicId,
      topOfDeck: topOfDeck,
    });
  }

  /**
   * Called when a player clicks on a develop from deck button
   *
   * @param genre the genre of the comic to develop
   */
  developComicFromDeck(genre: string): void {
    // Call the develop from deck action for the genre
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_DEVELOP_FROM_GENRE, {
      genre: genre,
    });
  }
}
