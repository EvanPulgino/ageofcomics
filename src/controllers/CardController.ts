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

class CardController extends GameBasics {
  setupPlayerHands(playerHands: any): void {
    for (var player_id in playerHands) {
      var hand = playerHands[player_id];
      for (var i in hand) {
        var card = hand[i];
        this.createCard(card);
      }
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
        case globalThis.LOCATION_HAND:
          this.createHtml(cardDiv, "aoc-hand-" + card.playerId);
          break;
      }
    } else {
      this.createHtml(cardDiv, location);
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
    if (card.location == globalThis.LOCATION_HAND) {
      this.createHtml(cardDiv, "aoc-hand-" + card.playerId);
    }
  }

  gainStartingComic(card: any): void {
    var location = "aoc-select-starting-comic-" + card.genre;
    this.createComicCard(card, location);
    this.slideCardToPlayerHand(card, location);
  }

  slideCardToPlayerHand(card: any, startLocation: string): void {
    var cardDiv = dojo.byId("aoc-card-" + card.id);
    var handDiv = dojo.byId("aoc-hand-" + card.playerId);
    var cardsInHand = dojo.query(".aoc-card", handDiv);
    var cardToRightOfNewCard = null;
    cardsInHand.forEach((cardInHand: any) => {
      if (cardInHand.getAttribute("order") > cardDiv.getAttribute("order")) {
        cardToRightOfNewCard = cardInHand;
      }
    });

    var animation = gameui.slideToObject(cardDiv, handDiv, 1000);
    dojo.connect(animation, "onEnd", () => {
      dojo.place(cardDiv, cardToRightOfNewCard, "before");
    });

    animation.play();
  }
}
