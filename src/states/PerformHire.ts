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
 * AgeOfComics perform hire state
 *
 * State vars:
 * - game: game object reference
 * - connections: object containing dojo connections
 *
 */
class PerformHire implements State {
  game: any;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = {};
  }

  /**
   * Called when entering this state
   * Creates possible hire actions
   *
   * stateArgs:
   * - isCurrentPlayerActive: true if this player is the active player
   *
   * args:
   * - canHireArtist: true if the player can hire an artist
   * - canHireWriter: true if the player can hire a writer
   *
   * @param stateArgs
   */
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

  /**
   * Called when leaving this state
   * Removes click listeners from cards
   */
  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");

    for (var key in this.connections) {
      dojo.disconnect(this.connections[key]);
    }

    this.connections = {};
  }

  onUpdateActionButtons(stateArgs: any): void {}

  /**
   * Creates possible hire actions
   *
   * @param creativeType - the type of creative to hire
   */
  createHireActions(creativeType: string): void {
    // Make top card of creative deck clickable and add click listener
    var topCardOfDeck = dojo.byId("aoc-" + creativeType + "-deck").lastChild;
    topCardOfDeck.classList.add("aoc-clickable");
    var topCardOfDeckId = topCardOfDeck.id.split("-")[2];
    this.connections[creativeType + topCardOfDeckId] = dojo.connect(
      dojo.byId(topCardOfDeck.id),
      "onclick",
      dojo.hitch(this, this.hireCreative, topCardOfDeckId, creativeType)
    );

    // Get all cards of the specified creative market
    var cardElements = dojo.byId(
      "aoc-" + creativeType + "s-available"
    ).children;

    // Make all cards in creative market clickable and add click listeners
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

  /**
   * Hires a creative
   *
   * @param cardId - the id of the card to hire
   * @param creativeType - the type of creative to hire
   */
  hireCreative(cardId: number, creativeType: string): void {
    // Call the hire creative action
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_HIRE_CREATIVE, {
      cardId: cardId,
      creativeType: creativeType,
    });
  }
}
