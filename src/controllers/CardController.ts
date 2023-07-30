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
    this.debug("createCard", card);
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

  createComicCard(card: any): void {
    var cardDiv =
      '<div id="aoc-card-' +
      card.id +
      '" class="aoc-comic-card ' +
      card.cssClass +
      '"></div>';
    if (card.location == globalThis.LOCATION_HAND) {
      this.createHtml(cardDiv, "aoc-hand-" + card.playerId);
    }
  }

  createCreativeCard(card: any): void {
    var cardDiv =
      '<div id="aoc-card-' +
      card.id +
      '" class="aoc-creative-card ' +
      card.cssClass +
      '"></div>';
    if (card.location == globalThis.LOCATION_HAND) {
      this.createHtml(cardDiv, "aoc-hand-" + card.playerId);
    }
  }
}