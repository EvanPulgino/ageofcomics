/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformSalesFulillOrder.ts
 *
 * AgeOfComics perform sales fulfill order state
 *
 */

class PerformSalesFulfillOrder implements State {
  game: any;
  connections: any;

  constructor(game: any) {
    this.game = game;
    this.connections = {};
  }

  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      const salesOrder = stateArgs.args.salesOrderBeingFulfilled;
      // Show the comic selection menu
      dojo.removeClass("aoc-select-comic-for-order-menu", "aoc-hidden");

      // Add tile to header
      const tileDiv =
        "<div id='sales-order-being-fulfilled' class='aoc-salesorder " +
        salesOrder.cssClass +
        "'></div>";
      dojo.place(tileDiv, "aoc-select-comic-for-order-header-text");

      // Add eligible comics to menu
      this.addComicsToMenu(stateArgs.args.cardsOnPlayerMat, salesOrder);
    }
  }
  
  onLeavingState(): void {
    dojo.empty("aoc-select-comics");
    dojo.destroy("sales-order-being-fulfilled");
    dojo.toggleClass("aoc-select-comic-for-order-menu", "aoc-hidden", true);
    for (const connection in this.connections) {
      dojo.disconnect(this.connections[connection]);
    }
    this.connections = {};
  }

  onUpdateActionButtons(stateArgs: any): void {}

  addComicsToMenu(cards: any[], salesOrder: any): void {
    const numOfComics = cards.filter(
      (card) => card.type === "comic" || card.type === "ripoff"
    ).length;

    for (let i = 1; i <= numOfComics; i++) {
      if (this.canFullfillOrder(i, cards, salesOrder)) {
        this.createActionColumn(i, cards, salesOrder);
      }
    }
  }

  canFullfillOrder(i: number, cards: any[], salesOrder: any): boolean {
    const comicCard = this.getComicCardInSlot(i, cards);
    if (comicCard.genre === salesOrder.genre) {
      const artistCard = this.getCreativeTypeCardInSlot("artist", i, cards);
      const writerCard = this.getCreativeTypeCardInSlot("writer", i, cards);
      const comicValue = artistCard.displayValue + writerCard.displayValue;
      if (comicValue >= salesOrder.value) {
        return true;
      }
    }
    return false;
  }

  createActionColumn(slot: number, cards: any[], salesOrder: any): void {
    const fulfillOrderColumn =
      "<div id='aoc-fulfill-order-column-" +
      slot +
      "' class='aoc-increase-action-column'></div>";
    dojo.place(fulfillOrderColumn, "aoc-select-comics");
    const selectableComicDiv =
      "<div id='aoc-selectable-comic-" +
      slot +
      "' class='aoc-increasable-comic-slot'></div>";
    dojo.place(selectableComicDiv, "aoc-fulfill-order-column-" + slot);

    // Add comic card to column
    this.createComicCardInColumn(slot, cards);
    // Add the artist card to the column
    this.createCreativeCardInColumn("artist", slot, cards);
    // Add the writer card to the column
    this.createCreativeCardInColumn("writer", slot, cards);

    // Create the fulfill order button
    const comicCard = this.getComicCardInSlot(slot, cards);

    const selectButtonDiv =
      "<a id='aoc-select-" +
      slot +
      "' class='action-button bgabutton bgabutton_blue aoc-button'>" +
      _("Select") +
      "</a>";
    dojo.place(selectButtonDiv, "aoc-fulfill-order-column-" + slot);

    // Connect the button
    this.connections["aoc-select-" + slot] = dojo.connect(
      dojo.byId("aoc-select-" + slot),
      "onclick",
      this,
      () => {
        this.select(comicCard.id, salesOrder.id);
      }
    );
  }

  createComicCardInColumn(slot: number, cards: any[]): void {
    const comicCard = this.getComicCardInSlot(slot, cards);
    if (comicCard) {
      const typeCss = "aoc-" + comicCard.type + "-card";
      const comicCardDiv =
        "<div id='aoc-inc-comic-card-" +
        comicCard.id +
        "' class='aoc-card " +
        typeCss +
        " " +
        comicCard.cssClass +
        "'></div>";
      dojo.place(comicCardDiv, "aoc-selectable-comic-" + slot);
    }
  }

  createCreativeCardInColumn(type: string, slot: number, cards: any[]): void {
    const creativeCard = this.getCreativeTypeCardInSlot(type, slot, cards);
    if (creativeCard) {
      const creativeCardDiv =
        "<div id='aoc-inc-" +
        type +
        "-card-" +
        creativeCard.id +
        "' class='aoc-card aoc-" +
        type +
        "-card aoc-creative-card " +
        creativeCard.cssClass +
        "'></div>";
      dojo.place(creativeCardDiv, "aoc-selectable-comic-" + slot);
      const increaseContainerDiv =
        "<div id='aoc-inc-improve-token-container-" +
        creativeCard.id +
        "' class='aoc-improve-token-container'></div>";
      dojo.place(
        increaseContainerDiv,
        "aoc-inc-" + type + "-card-" + creativeCard.id
      );
      if (creativeCard.displayValue > creativeCard.value) {
        const increaseTokenCssClass =
          "aoc-token-increase-" +
          creativeCard.type +
          "-" +
          creativeCard.displayValue;
        const increaseTokenDiv =
          "<div id='aoc-inc-improve-token-" +
          creativeCard.id +
          "' class='aoc-increase-token " +
          increaseTokenCssClass +
          "'></div>";
        dojo.place(
          increaseTokenDiv,
          "aoc-inc-improve-token-container-" + creativeCard.id
        );
      }
    }
  }

  getCreativeTypeCardInSlot(type: string, slot: number, cards: any[]): any {
    return cards.find(
      (card) => card.type === type && card.locationArg === slot
    );
  }

  getComicCardInSlot(slot: number, cards: any[]): any {
    return cards.find(
      (card) =>
        (card.type === "comic" || card.type === "ripoff") &&
        card.locationArg === slot
    );
  }

  select(comicId: number, salesOrderId: number): void {
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_SELECT_COMIC_FOR_ORDER, {
      comicId,
      salesOrderId,
    });
    this.onLeavingState();
  }
}
