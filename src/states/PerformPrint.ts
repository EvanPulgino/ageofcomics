/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformPrint.ts
 *
 * Age of Comics perform print state
 *
 * State vars:
 *  game: game object reference
 *
 */
class PerformPrint implements State {
  game: any;
  shouldUnselectArtists: boolean;
  shouldUnselectComics: boolean;
  shouldUnselectWriters: boolean;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = {};
  }

  /**
   * Called when entering this state
   * Creates the print menu and events
   *
   * stateArgs:
   *  - isCurrentPlayerActive: true if this player is the active player
   *
   * args:
   * - printableComics: comics the player can print
   * - artists: artists the player can use
   * - writers: writers the player can use
   *
   * @param stateArgs
   */
  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      dojo.toggleClass("aoc-print-menu", "aoc-hidden");
      this.createCards(stateArgs.args.printableComics, "comic");
      this.createCards(stateArgs.args.artists, "artist");
      this.createCards(stateArgs.args.writers, "writer");
    }
  }

  /**
   * Called when leaving this state
   * Removes the print menu and events
   *
   */
  onLeavingState(): void {
    // Hide the print menu
    dojo.toggleClass("aoc-print-menu", "aoc-hidden", true);

    // Remove the css classes from the comics
    dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
    dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");

    // Remove the listeners
    for (const connection in this.connections) {
      dojo.disconnect(this.connections[connection]);
    }

    // Clear the menu
    dojo.empty("aoc-print-comics-menu");
    dojo.empty("aoc-print-artists-menu");
    dojo.empty("aoc-print-writers-menu");
  }

  /**
   * Called when game enters the state. Creates confirmation button for the state
   *
   * stateArgs:
   *  - isCurrentPlayerActive: true if this player is the active player
   *
   * args:
   * - printableComics: comics the player can print
   * - artists: artists the player can use
   * - writers: writers the player can use
   *
   * @param stateArgs
   */
  onUpdateActionButtons(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      gameui.addActionButton("aoc-confirm-print", _("Confirm"), () => {
        this.confirmPrint();
      });
      dojo.addClass("aoc-confirm-print", "aoc-button-disabled");
      dojo.addClass("aoc-confirm-print", "aoc-button");
    }
  }

  /**
   * Called when the confirm button is clicked
   * Sends the selected cards to the server
   */
  confirmPrint() {
    // Get the selected cards
    const selectedComic = dojo.query(
      "#aoc-print-comics-menu > .aoc-card-selected"
    )[0];
    const selectedArtist = dojo.query(
      "#aoc-print-artists-menu > .aoc-card-selected"
    )[0];
    const selectedWriter = dojo.query(
      "#aoc-print-writers-menu > .aoc-card-selected"
    )[0];

    // Get the ids of the selected cards
    const selectedComicId = selectedComic.id.split("-")[4];
    const selectedArtistId = selectedArtist.id.split("-")[4];
    const selectedWriterId = selectedWriter.id.split("-")[4];

    // Send the selected cards to the server
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_PRINT_COMIC, {
      comicId: selectedComicId,
      artistId: selectedArtistId,
      writerId: selectedWriterId,
    });

    this.onLeavingState();
  }

  /**
   * Create cards for the print menu
   *
   * @param cards Array of cards to create
   * @param cardType Type of card to create
   */
  createCards(cards: any, cardType: string): void {
    // Get the card type class
    const cardTypeClass =
      cardType == "comic" ? "aoc-comic-card" : "aoc-creative-card";

    // Create a div for each card
    for (const card of cards) {
      const cardDiv =
        "<div id='aoc-print-menu-" +
        cardType +
        "-" +
        card.id +
        "' class='aoc-card " +
        cardTypeClass +
        " " +
        card.baseClass +
        "'></div>";

      // Add the div to the menu
      this.game.createHtml(cardDiv, "aoc-print-" + cardType + "s-menu");

      // Add the card's listener
      this.connections[card.id] = dojo.connect(
        dojo.byId("aoc-print-menu-" + cardType + "-" + card.id),
        "onclick",
        this,
        () => {
          this.handleCardSelection(card.id, cardType);
        }
      );
    }
  }

  /**
   * Handle card selection
   *
   * @param cardId Id of the card that was selected
   * @param cardType Type of card that was selected
   */
  handleCardSelection(cardId: number, cardType: string): void {
    // Remove the selected and unselected classes from all cards of type
    dojo
      .query("#aoc-print-" + cardType + "s-menu > .aoc-card-selected")
      .removeClass("aoc-card-selected");
    dojo
      .query("#aoc-print-" + cardType + "s-menu > .aoc-card-unselected")
      .removeClass("aoc-card-unselected");

    const selectedCardDiv = dojo.byId(
      "aoc-print-menu-" + cardType + "-" + cardId
    );

    // Add the selected class to the selected card
    dojo.toggleClass(selectedCardDiv, "aoc-card-selected");
    // Disconnect + delete the card's listener
    dojo.disconnect(this.connections[cardId]);
    delete this.connections[cardId];

    // Get all cards of type
    const allCards = dojo.byId("aoc-print-" + cardType + "s-menu").children;
    for (let i = 0; i < allCards.length; i++) {
      const card = allCards[i];
      // Check if the card is not the selected card
      if (card.id != selectedCardDiv.id) {
        const cardDivId = card.id.split("-")[4];
        // Add the unselected class to the comic
        dojo.toggleClass(card.id, "aoc-card-unselected", true);
        //If the comic doesn't have a listener, add one
        if (!this.connections[cardDivId]) {
          this.connections[cardDivId] = dojo.connect(
            dojo.byId(card.id),
            "onclick",
            this,
            () => {
              this.handleCardSelection(cardDivId, cardType);
            }
          );
        }
      }
    }

    // Set confirm button status
    this.setButtonConfirmationStatus();
  }

  /**
   * Sets the status of the confirm button
   *
   * If 3 cards are selected, the confirm button is enabled
   */
  setButtonConfirmationStatus(): void {
    const selectedCards = dojo.query(".aoc-card-selected");
    if (selectedCards.length == 3) {
      dojo.removeClass("aoc-confirm-print", "aoc-button-disabled");
    } else {
      dojo.addClass("aoc-confirm-print", "aoc-button-disabled");
    }
  }
}
