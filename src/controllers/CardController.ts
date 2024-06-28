/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * CardController.ts
 *
 * Handles all front end interactions with the cards
 *
 */

class CardController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  /**
   * Setup all cards
   *
   * @param cards - the cards to setup
   */
  setupCards(cards: any[]): void {
    // Sort cards by locationArg
    cards.sort((a, b) => {
      return a.locationArg - b.locationArg;
    });
    // Create each card
    for (var i in cards) {
      var card = cards[i];
      this.createNewCard(card);
    }
  }

  /**
   * Create a new card
   *
   * @param card - the card to create
   * @param location - the location to create the card in
   */
  createNewCard(card: any, location?: string): void {
    // Create the card div
    const cardDiv = this.createCardDiv(card);

    // If a location is provided, create the card in that location
    if (location) {
      this.ui.createHtml(cardDiv, location);
      return;
    }

    // Otherwise, create the card in the appropriate location based on the card's location attribute
    switch (card.location) {
      case globalThis.LOCATION_DECK:
        this.ui.createHtml(cardDiv, "aoc-" + card.type + "-deck");
        break;
      case globalThis.LOCATION_DISCARD:
        this.ui.createHtml(cardDiv, "aoc-" + card.type + "s-discard");
        break;
      case globalThis.LOCATION_HAND:
        this.ui.createHtml(cardDiv, "aoc-hand-" + card.playerId);
        break;
      case globalThis.LOCATION_SUPPLY:
        this.ui.createHtml(cardDiv, "aoc-" + card.type + "s-available");
        break;
      case globalThis.LOCATION_PLAYER_MAT:
        const cardType = this.getCardTypeForMatSlot(card);
        this.ui.createHtml(
          cardDiv,
          "aoc-" + cardType + "-slot-" + card.locationArg + "-" + card.playerId
        );
        break;
    }
  }

  /**
   * Create a new element
   *
   * @param card - the card to create
   * @returns the card div
   */
  createCardDiv(card: any): string {
    const id = "aoc-card-" + card.id;
    const css = this.getCardDivCss(card);
    const order = card.locationArg;

    let cardDiv = `<div id="${id}" class="${css}" order="${order}">`;

    if (card.type === "artist" || card.type === "writer") {
      const improveTokenContainerId = "aoc-improve-token-container-" + card.id;
      cardDiv += `<div id="${improveTokenContainerId}" class="aoc-improve-token-container">`;

      if (card.displayValue > card.value) {
        const improveTokenDivId = "aoc-improve-token-" + card.id;
        const improveTokenCssClass = `aoc-token-increase-${card.type}-${card.displayValue}`;
        cardDiv += `<div id="${improveTokenDivId}" class="aoc-increase-token ${improveTokenCssClass}"></div>`;
      }

      cardDiv += `</div>`;
    }

    cardDiv += `</div>`;

    return cardDiv;
  }

  /**
   * Get the css class for a card based on its type
   *
   * @param card - the card to get the css class for
   * @returns the css class
   */
  getCardDivCss(card: any): string {
    return (
      "aoc-card " +
      card.cssClass +
      " " +
      this.getCardTypeCss(card.type) +
      " " +
      card.cssClass
    );
  }

  /**
   * Get the css class for a card based on its type
   *
   * @param cardType - the card type to get the css class for
   * @returns the css class
   */
  getCardTypeCss(cardType: string): string {
    switch (cardType) {
      case "artist":
        return "aoc-creative-card";
      case "writer":
        return "aoc-creative-card";
      case "comic":
        return "aoc-comic-card";
      case "ripoff":
        return "aoc-ripoff-card";
    }
  }

  dealCardToSupply(card: any): void {
    const cardType = card.type;
    const cardDiv = dojo.byId("aoc-card-" + card.id);
    const supplyDiv = dojo.byId("aoc-" + cardType + "s-available");

    // Flip the card face-up
    cardDiv.classList.remove(card.facedownClass);
    cardDiv.classList.add(card.baseClass);
    dojo.setAttr(cardDiv, "order", card.locationArg);

    // Create the animation
    var animation = gameui.slideToObject(cardDiv, supplyDiv, 500);
    dojo.connect(animation, "onEnd", () => {
      // After animation ends, remove styling added by animation and place in new parent div
      dojo.removeAttr(cardDiv, "style");
      dojo.place(cardDiv, supplyDiv);
    });

    // Play the animation
    animation.play();
  }

  addImproveToken(card: any): void {
    // First remove any existing improve token
    dojo.empty("aoc-improve-token-container-" + card.id);

    // Create the improve token
    const improveTokenDivId = "aoc-improve-token-" + card.id;
    const improveTokenCssClass = `aoc-token-increase-${card.type}-${card.displayValue}`;
    const tokenDiv = `<div id="${improveTokenDivId}" class="aoc-increase-token ${improveTokenCssClass}"></div>`;
    this.ui.createHtml(tokenDiv, "aoc-improve-token-container-" + card.id);
  }

  /**
   * Moves card from a player's hand to the appropriate discard pile.
   *
   * @param card - the card to discard
   * @param playerId - the player who is discarding the card
   */
  discardCard(card: any, playerId: any): void {
    // Get the card div
    var cardDiv = dojo.byId("aoc-card-" + card.id);

    // Move card out of overlay to allow animation
    dojo.place(cardDiv, "aoc-player-area-right-" + playerId);

    // If the card is face down, flip it face up
    if (cardDiv.classList.contains(card.facedownClass)) {
      cardDiv.classList.remove(card.facedownClass);
      cardDiv.classList.add(card.baseClass);
    }

    // Get the discard pile for the card's type
    var discardDiv = dojo.byId("aoc-" + card.type + "s-discard");

    // Create the animation
    var animation = gameui.slideToObject(cardDiv, discardDiv, 500);
    dojo.connect(animation, "onEnd", () => {
      // After animation ends, remove styling added by animation and place in new parent div
      dojo.removeAttr(cardDiv, "style");
      dojo.place(cardDiv, discardDiv);
    });

    // Play the animation
    animation.play();
  }

  /**
   * Moves card from the top of a deck to the appropriate discard pile.
   *
   * @param card - the card to discard
   */
  discardCardFromDeck(card: any): void {
    // Get the card div
    var cardDiv = dojo.byId("aoc-card-" + card.id);

    // Flip the card face-up
    cardDiv.classList.remove(card.facedownClass);
    cardDiv.classList.add(card.baseClass);

    // Get the discard pile for the card's type
    var discardDiv = dojo.byId("aoc-" + card.type + "s-discard");

    // Create the animation
    var animation = gameui.slideToObject(cardDiv, discardDiv, 500);
    dojo.connect(animation, "onEnd", () => {
      // After animation ends, remove styling added by animation and place in new parent div
      dojo.removeAttr(cardDiv, "style");
      dojo.place(cardDiv, discardDiv);
    });

    // Play the animation
    animation.play();
  }

  discardCardFromSupply(card: any): void {
    // Get the card div
    var cardDiv = dojo.byId("aoc-card-" + card.id);

    // Get the discard pile for the card's type
    var discardDiv = dojo.byId("aoc-" + card.type + "s-discard");

    // Create the animation
    var animation = gameui.slideToObject(cardDiv, discardDiv, 500);
    dojo.connect(animation, "onEnd", () => {
      // After animation ends, remove styling added by animation and place in new parent div
      dojo.removeAttr(cardDiv, "style");
      dojo.place(cardDiv, discardDiv);
    });

    // Play the animation
    animation.play();
  }

  /**
   * A player gains their starting comic card
   *
   * @param card - the card to gain
   */
  gainStartingComic(card: any): void {
    // Get the location of the card selection area
    var location = "aoc-select-starting-comic-" + card.genre;

    // Create the card
    this.createNewCard(card, location);

    // Slide the card to the player's hand
    this.slideCardToPlayerHand(card);
  }

  getCardTypeForMatSlot(card: any) {
    switch (card.typeId) {
      case globalThis.CARD_TYPE_ARTIST:
        return "artist";
      case globalThis.CARD_TYPE_WRITER:
        return "writer";
      case globalThis.CARD_TYPE_COMIC:
        return "comic";
      case globalThis.CARD_TYPE_RIPOFF:
        return "comic";
    }
  }

  /**
   * Moves a card element to a player's hand
   *
   * @param card - the card to move
   */
  slideCardToPlayerHand(card: any): void {
    // Get the card div
    var cardDiv = dojo.byId("aoc-card-" + card.id);

    // Set the card face up or face down depeding on the card's css class
    var facedownCss = card.facedownClass;
    var baseCss = card.baseClass;
    if (
      cardDiv.classList.contains(facedownCss) &&
      card.cssClass !== facedownCss
    ) {
      cardDiv.classList.remove(facedownCss);
      cardDiv.classList.add(card.cssClass);
    }
    if (
      !cardDiv.classList.contains(facedownCss) &&
      card.cssClass === facedownCss
    ) {
      cardDiv.classList.remove(baseCss);
      cardDiv.classList.add(facedownCss);
    }

    // Add an order attribute to the card div
    dojo.setAttr(cardDiv, "order", card.locationArg);

    // Get the hand div
    var handDiv = dojo.byId("aoc-hand-" + card.playerId);

    // Get the card divs in the hand
    var cardsInHand = dojo.query(".aoc-card", handDiv);

    // Get the card div to the right of the new card's location
    var cardToRightOfNewCard = null;
    cardsInHand.forEach((cardInHand: any) => {
      if (
        cardToRightOfNewCard == null &&
        cardInHand.getAttribute("order") > cardDiv.getAttribute("order")
      ) {
        cardToRightOfNewCard = cardInHand;
      }
    });

    // Create the animation
    var animation = gameui.slideToObject(cardDiv, handDiv, 500);
    dojo.connect(animation, "onEnd", () => {
      // After animation ends, remove styling added by animation and place in new parent div
      dojo.removeAttr(cardDiv, "style");
      if (cardToRightOfNewCard == null) {
        dojo.place(cardDiv, handDiv);
      } else {
        dojo.place(cardDiv, cardToRightOfNewCard, "before");
      }
    });

    // Play the animation
    animation.play();
  }

  slideCardToPlayerMat(player: any, card: any, slot: number): void {
    // If card is ripoff, create div
    if (card.typeId === globalThis.CARD_TYPE_RIPOFF) {
      this.createNewCard(card, "aoc-overall");
    }

    // Get the card div
    const cardDiv = dojo.byId("aoc-card-" + card.id);

    const cardType = this.getCardTypeForMatSlot(card);

    // Set the card faceup
    if (cardDiv.classList.contains(card.facedownClass)) {
      cardDiv.classList.remove(card.facedownClass);
      cardDiv.classList.add(card.baseClass);
    }

    // Get the player mat slot div
    const slotDiv = dojo.byId(
      "aoc-" + cardType + "-slot-" + slot + "-" + player.id
    );

    // Create the animation
    var animation = gameui.slideToObject(cardDiv, slotDiv, 1000);
    dojo.connect(animation, "onEnd", () => {
      // After animation ends, remove styling added by animation and place in new parent div
      dojo.removeAttr(cardDiv, "style");
      dojo.place(cardDiv, slotDiv);
    });

    // Play the animation
    animation.play();
  }
}
