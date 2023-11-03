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
 */

class CheckHandSize implements State {
  game: any;
  numberToDiscard: number;
  unselect: boolean;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.numberToDiscard = 0;
    this.unselect = false;
    this.connections = {};
  }

  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      this.numberToDiscard = stateArgs.args.numberToDiscard;
      var cardsInHand = dojo.byId(
        "aoc-hand-" + stateArgs.active_player
      ).children;

      for (var i = 0; i < cardsInHand.length; i++) {
        dojo.addClass(cardsInHand[i], "aoc-clickable");
        this.connections[cardsInHand[i].id] = dojo.connect(
          cardsInHand[i],
          "onclick",
          dojo.hitch(this, this.selectCard, cardsInHand[i])
        );
      }
    }
  }

  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");
    dojo.query(".aoc-selected").removeClass("aoc-selected");

    for (var key in this.connections) {
      dojo.disconnect(this.connections[key]);
    }

    this.connections = {};
  }

  onUpdateActionButtons(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      gameui.addActionButton("aoc-confirm-discard", _("Confirm"), (event) => {
        this.confirmDiscard(event);
      });
      dojo.addClass("aoc-confirm-discard", "aoc-button-disabled");
      dojo.addClass("aoc-confirm-discard", "aoc-button");
    }
  }

  confirmDiscard(event): void {
    console.log("confirm discard");
  }

  selectCard(card: HTMLElement): void {
    dojo.toggleClass(card, "aoc-clickable");
    dojo.toggleClass(card, "aoc-selected");

    this.updateConfirmationButtonStatus();
  }

  toggleCardStatus(): void {
    var unselectedCards = dojo.query(".aoc-clickable");
    for (var i = 0; i < unselectedCards.length; i++) {
      var unselectedCard = unselectedCards[i];
      dojo.toggleClass(unselectedCard, "aoc-clickable");
      dojo.disconnect(this.connections[unselectedCard.id]);
    }
    this.unselect = true;
  }

  untoggleCardStatus(): void {
    var cardsToUntoggle = dojo.query(
      "div#aoc-hand-" +
        this.game.player_id +
        "> .aoc-card:not(.aoc-selected):not(.aoc-clickable)"
    );
    for (var i = 0; i < cardsToUntoggle.length; i++) {
      var card = cardsToUntoggle[i];
      dojo.toggleClass(card, "aoc-clickable");
      this.connections[card.id] = dojo.connect(
        card,
        "onclick",
        dojo.hitch(this, this.selectCard, card)
      );
    }
  }

  updateConfirmationButtonStatus(): void {
    var selectedCards = dojo.query(".aoc-selected");
    if (selectedCards.length == this.numberToDiscard) {
      this.toggleCardStatus();
      dojo.removeClass("aoc-confirm-discard", "aoc-button-disabled");
    } else {
      dojo.addClass("aoc-confirm-discard", "aoc-button-disabled");
      if (this.unselect) {
        this.untoggleCardStatus();
      }
    }
  }
}
