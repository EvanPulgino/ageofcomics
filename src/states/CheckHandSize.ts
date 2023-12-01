/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * CheckHandSize.ts
 *
 * AgeOfComics check hand size state
 *
 * State vars:
 * - game: game object reference
 * - numberToDiscard: number of cards to discard
 * - shouldUnselect: true if cards should be unselected when the number of selected cards is less than the number of cards to discard
 * - connections: map of card id to click listener
 *
 */
class CheckHandSize implements State {
  game: any;
  numberToDiscard: number;
  shouldUnselect: boolean;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.numberToDiscard = 0;
    this.shouldUnselect = false;
    this.connections = {};
  }

  /**
   * Called when entering this state.
   *
   * If the current player is active, add click listeners to the cards in their hand.
   * This is done after a timeout to allow drawn cards from previous state to enter the player hand.
   *
   * stateArgs:
   *  - isCurrentPlayerActive: true if this player is the active player
   *
   * args:
   * - numberToDiscard: number of cards to discard
   *
   * @param stateArgs contains args derived from the state machine
   */
  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      // Set number of cards to discard variable
      this.numberToDiscard = stateArgs.args.numberToDiscard;

      // Get all cards in hand
      var cardsInHand = dojo.byId(
        "aoc-hand-" + stateArgs.active_player
      ).children;

      // After a timeout, add click listeners to cards in hand
      setTimeout(() => {
        for (var i = 0; i < cardsInHand.length; i++) {
          dojo.addClass(cardsInHand[i], "aoc-clickable");
          this.connections[cardsInHand[i].id] = dojo.connect(
            cardsInHand[i],
            "onclick",
            dojo.hitch(this, this.selectCard, cardsInHand[i])
          );
        }
      }, 1000);
    }
  }

  /**
   * Called when leaving this state.
   *
   * Make all cards unclickable and unselected.
   * Remove click listeners from cards in hand.
   */
  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");
    dojo.query(".aoc-selected").removeClass("aoc-selected");

    for (var key in this.connections) {
      dojo.disconnect(this.connections[key]);
    }

    this.connections = {};
  }

  /**
   * Called when the action buttons need to be updated.
   *
   * If the current player is active, add a confirmation button that starts disabled.
   *
   * stateArgs:
   *  - isCurrentPlayerActive: true if this player is the active player
   *
   * @param stateArgs contains args derived from the state machine
   */
  onUpdateActionButtons(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      // Add confirmation button
      gameui.addActionButton("aoc-confirm-discard", _("Confirm"), () => {
        this.confirmDiscard();
      });

      // Disable confirmation button + add custom styling
      dojo.addClass("aoc-confirm-discard", "aoc-button-disabled");
      dojo.addClass("aoc-confirm-discard", "aoc-button");
    }
  }

  /**
   * Called when the confirmation button is clicked.
   *
   * Send the selected cards to the server.
   */
  confirmDiscard(): void {
    // Initialize string to store card ids in comma separated list
    var cardsToDiscard = "";

    // Get all cards with the `selected` class
    var selectedCards = dojo.query(".aoc-selected");

    // For each card, add its id to the string
    for (var i = 0; i < selectedCards.length; i++) {
      var card = selectedCards[i];
      if (i == 0) {
        cardsToDiscard += card.id.split("-")[2];
      } else {
        cardsToDiscard += "," + card.id.split("-")[2];
      }
    }

    // Send the card ids to the server
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_CONFIRM_DISCARD, {
      cardsToDiscard: cardsToDiscard,
    });
  }

  /**
   * Called when a card is clicked.
   *
   * If the card is clickable, toggle its selected status.
   *
   * @param card the card that was clicked
   */
  selectCard(card: HTMLElement): void {
    // Toggle clicabile and selected status
    dojo.toggleClass(card, "aoc-clickable");
    dojo.toggleClass(card, "aoc-selected");

    // Update confirmation button status
    this.updateConfirmationButtonStatus();
  }

  /**
   * Called when the number of selected cards is equal to the number of cards to discard.
   *
   * Make all unselected cards unclickable.
   * Disconnect click listeners from unselected cards.
   *
   * This is done to prevent the player from selecting more cards than they are able to discard.
   */
  toggleCardStatus(): void {
    // Get all unselected cards
    var unselectedCards = dojo.query(".aoc-clickable");

    // For each card, make it unclickable and disconnect its click listener
    for (var i = 0; i < unselectedCards.length; i++) {
      var unselectedCard = unselectedCards[i];
      dojo.toggleClass(unselectedCard, "aoc-clickable");
      dojo.disconnect(this.connections[unselectedCard.id]);
    }

    // Set shouldUnselect to true to indicate that cards should be unselected when the number
    // of selected cards is less than the number of cards to discard
    this.shouldUnselect = true;
  }

  /**
   * Called when the number of selected cards is less than the number of cards to discard.
   *
   * Make all unselected cards clickable.
   * Add click listeners to unselected cards.
   */
  untoggleCardStatus(): void {
    // Get all unselected cards - aka all cards that are not selected and not clickable
    var cardsToUntoggle = dojo.query(
      "div#aoc-hand-" +
        this.game.player_id +
        "> .aoc-card:not(.aoc-selected):not(.aoc-clickable)"
    );

    // For each card, make it clickable and add a click listener
    for (var i = 0; i < cardsToUntoggle.length; i++) {
      var card = cardsToUntoggle[i];
      dojo.toggleClass(card, "aoc-clickable");
      this.connections[card.id] = dojo.connect(
        card,
        "onclick",
        dojo.hitch(this, this.selectCard, card)
      );
    }

    // Set shouldUnselect to false to indicate that cards should not be unselected when the number
    // of selected cards is less than the number of cards to discard
    this.shouldUnselect = false;
  }

  /**
   * Called when the number of selected cards changes.
   *
   * If the number of selected cards is equal to the number of cards to discard, make all unselected cards unclickable.
   * If the number of selected cards is less than the number of cards to discard, make all unselected cards clickable.
   */
  updateConfirmationButtonStatus(): void {
    // Get all selected cards
    var selectedCards = dojo.query(".aoc-selected");

    // If the number of selected cards is equal to the number of cards to discard,
    // make all unselected cards unclickable, then enable the confirmation button
    if (selectedCards.length == this.numberToDiscard) {
      this.toggleCardStatus();
      dojo.removeClass("aoc-confirm-discard", "aoc-button-disabled");
    } else {
      // If the number of selected cards is less than the number of cards to discard disable the confirmation button
      dojo.addClass("aoc-confirm-discard", "aoc-button-disabled");
      
      // If shouldUnselect is true, make all unselected cards clickable
      if (this.shouldUnselect) {
        this.untoggleCardStatus();
      }
    }
  }
}
