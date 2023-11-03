/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformHire.ts
 *
 */

class PerformHire implements State {
  game: any;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = {};
  }

  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      if (stateArgs.args.canHireArtist == 1) {
        this.createHireActions("artist");
      }
      if (stateArgs.args.canHireWriter == 1) {
        this.createHireActions("writer");
      }
    }
  }

  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");

    for (var key in this.connections) {
      dojo.disconnect(this.connections[key]);
    }

    this.connections = {};
  }

  onUpdateActionButtons(stateArgs: any): void {}

  createHireActions(creativeType: string): void {
    var topCardOfDeck = dojo.byId("aoc-" + creativeType + "-deck").lastChild;
    topCardOfDeck.classList.add("aoc-clickable");
    var topCardOfDeckId = topCardOfDeck.id.split("-")[2];
    this.connections[creativeType + topCardOfDeckId] = dojo.connect(
      dojo.byId(topCardOfDeck.id),
      "onclick",
      dojo.hitch(this, this.hireCreative, topCardOfDeckId, creativeType)
    );

    var cardElements = dojo.byId(
      "aoc-" + creativeType + "s-available"
    ).children;

    for (var key in cardElements) {
      var card = cardElements[key];
      if (card.id) {
        card.classList.add("aoc-clickable");
        var cardId = card.id.split("-")[2];
        this.connections[creativeType + cardId] = dojo.connect(
          dojo.byId(card.id),
          "onclick",
          dojo.hitch(this, this.hireCreative, cardId, creativeType)
        );
      }
    }
  }

  hireCreative(cardId: number, creativeType: string): void {
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_HIRE_CREATIVE, {
      cardId: cardId,
      creativeType: creativeType,
    });
  }
}
