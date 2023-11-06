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
 */

class CardController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  setupPlayerHands(playerHands: any): void {
    for (var player_id in playerHands) {
      var hand = playerHands[player_id];
      for (var i in hand) {
        var card = hand[i];
        this.createCard(card);
      }
    }
  }

  setupDeck(deck: any): void {
    for (var i in deck) {
      var card = deck[i];
      this.createCard(card);
    }
  }

  setupSupply(cardSupply: any): void {
    for (var i in cardSupply) {
      var card = cardSupply[i];
      this.createCard(card);
    }
  }

  createCard(card: any): void {
    switch (card.typeId) {
      case 1:
        this.createCreativeCard(card);
        break;
      case 2:
        this.createCreativeCard(card);
        break;
      case 3:
        this.createComicCard(card);
        break;
    }
  }

  createComicCard(card: any, location?: any): void {
    var cardDiv =
      '<div id="aoc-card-' +
      card.id +
      '" class="aoc-card aoc-comic-card ' +
      card.cssClass +
      '" order="' +
      card.locationArg +
      '"></div>';

    if (!location) {
      switch (card.location) {
        case globalThis.LOCATION_DECK:
          this.ui.createHtml(cardDiv, "aoc-" + card.type + "-deck");
          break;
        case globalThis.LOCATION_HAND:
          this.ui.createHtml(cardDiv, "aoc-hand-" + card.playerId);
          break;
        case globalThis.LOCATION_SUPPLY:
          this.ui.createHtml(cardDiv, "aoc-" + card.type + "s-available");
          break;
      }
    } else {
      this.ui.createHtml(cardDiv, location);
    }
  }

  createCreativeCard(card: any): void {
    var cardDiv =
      '<div id="aoc-card-' +
      card.id +
      '" class="aoc-card aoc-creative-card ' +
      card.cssClass +
      '" order="' +
      card.locationArg +
      '"></div>';
    switch (card.location) {
      case globalThis.LOCATION_DECK:
        this.ui.createHtml(cardDiv, "aoc-" + card.type + "-deck");
        break;
      case globalThis.LOCATION_HAND:
        this.ui.createHtml(cardDiv, "aoc-hand-" + card.playerId);
        break;
      case globalThis.LOCATION_SUPPLY:
        this.ui.createHtml(cardDiv, "aoc-" + card.type + "s-available");
        break;
    }
  }

  discardCard(card: any, playerId: any): void {
    var cardDiv = dojo.byId("aoc-card-" + card.id);
    dojo.place(cardDiv, "aoc-player-area-right-" + playerId);
    var discardDiv = dojo.byId("aoc-game-status-panel");
    gameui.slideToObjectAndDestroy(cardDiv, discardDiv, 500);
  }

  discardCardFromDeck(card: any): void {
    var cardDiv = dojo.byId("aoc-card-" + card.id);
    var discardDiv = dojo.byId("aoc-game-status-panel");
    gameui.slideToObjectAndDestroy(cardDiv, discardDiv, 500);
  }

  gainStartingComic(card: any): void {
    var location = "aoc-select-starting-comic-" + card.genre;
    this.createComicCard(card, location);
    this.slideCardToPlayerHand(card);
  }

  slideCardToPlayerHand(card: any): void {
    var cardDiv = dojo.byId("aoc-card-" + card.id);
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

    dojo.setAttr(cardDiv, "order", card.locationArg);

    var handDiv = dojo.byId("aoc-hand-" + card.playerId);
    var cardsInHand = dojo.query(".aoc-card", handDiv);
    var cardToRightOfNewCard = null;
    cardsInHand.forEach((cardInHand: any) => {
      if (
        cardToRightOfNewCard == null &&
        cardInHand.getAttribute("order") > cardDiv.getAttribute("order")
      ) {
        cardToRightOfNewCard = cardInHand;
      }
    });

    var animation = gameui.slideToObject(cardDiv, handDiv, 500);
    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(cardDiv, "style");
      if (cardToRightOfNewCard == null) {
        dojo.place(cardDiv, handDiv);
      } else {
        dojo.place(cardDiv, cardToRightOfNewCard, "before");
      }
    });

    animation.play();
  }
}
