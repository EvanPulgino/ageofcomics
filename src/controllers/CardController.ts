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

  setupCards(cards: any[]): void {
    cards.sort((a, b) => {
      return a.locationArg - b.locationArg;
    });
    for (var i in cards) {
      var card = cards[i];
      this.createNewCard(card);
    }
  }

  createNewCard(card: any, location?: string): void {
    const cardDiv = this.createCardDiv(card);

    if (location) {
      this.ui.createHtml(cardDiv, location);
      return;
    }

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
    }
  }

  createCardDiv(card: any): string {
    const id = "aoc-card-" + card.id;
    const css = this.getCardDivCss(card);
    const order = card.locationArg;

    return `<div id="${id}" class="${css}" order="${order}"></div>`;
  }

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

  discardCard(card: any, playerId: any): void {
    var cardDiv = dojo.byId("aoc-card-" + card.id);
    dojo.place(cardDiv, "aoc-player-area-right-" + playerId);
    if (cardDiv.classList.contains(card.facedownClass)) {
      cardDiv.classList.remove(card.facedownClass);
      cardDiv.classList.add(card.baseClass);
    }
    var discardDiv = dojo.byId("aoc-" + card.type + "s-discard");
    gameui.slideToObjectAndDestroy(cardDiv, discardDiv, 500);
    var animation = gameui.slideToObject(cardDiv, discardDiv, 500);
    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(cardDiv, "style");
      dojo.place(cardDiv, discardDiv);
    });
    animation.play();
  }

  discardCardFromDeck(card: any): void {
    var cardDiv = dojo.byId("aoc-card-" + card.id);
    cardDiv.classList.remove(card.facedownClass);
    cardDiv.classList.add(card.baseClass);
    var discardDiv = dojo.byId("aoc-" + card.type + "s-discard");
    var animation = gameui.slideToObject(cardDiv, discardDiv, 500);
    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(cardDiv, "style");
      dojo.place(cardDiv, discardDiv);
    });
    animation.play();
  }

  gainStartingComic(card: any): void {
    var location = "aoc-select-starting-comic-" + card.genre;
    this.createNewCard(card, location);
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
