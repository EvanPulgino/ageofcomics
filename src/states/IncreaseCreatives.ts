/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * IncreaseCreatives.ts
 *
 * AgeOfComics increase creatives state
 *
 * State vars:
 * - game: game object reference
 *
 */
class IncreaseCreatives implements State {
  game: any;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = {};
  }

  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      this.createEndActionButton(parseInt(stateArgs.args.currentPlayer.id));

      dojo.toggleClass("aoc-improve-creatives-menu", "aoc-hidden");
      this.addComicsToMenu(
        stateArgs.args.cardsOnPlayerMat,
        parseInt(stateArgs.args.currentPlayer.money)
      );
    }
  }
  onLeavingState(): void {
    dojo.empty("aoc-improve-creatives-button-container");
    dojo.empty("aoc-improve-creatives-comics");
    dojo.toggleClass("aoc-improve-creatives-menu", "aoc-hidden", true);
    for (const connection in this.connections) {
      dojo.disconnect(this.connections[connection]);
    }
    this.connections = {};
  }

  onUpdateActionButtons(stateArgs: any): void {}

  addComicsToMenu(cards: any[], playerMoney: number): void {
    const numOfComics = cards.filter(
      (card) => card.type === "comic" || card.type === "ripoff"
    ).length;
    for (let i = 1; i <= numOfComics; i++) {
      this.createActionColumn(i, cards, playerMoney);
    }
  }

  /**
   * Check if a creative on this comic can learn
   *
   * A creative can learn if:
   * - Both creatives match the genre of the comic
   * - The creatives have different values
   *
   * @param slot - the slot of the comic on the player mat
   * @param cards - the cards on the player mat
   *
   * @returns boolean - true if a creative can learn
   */
  canLearn(slot: number, cards: any[]): boolean {
    const comicCard = this.getComicCardInSlot(slot, cards);
    const artistCard = this.getCreativeTypeCardInSlot("artist", slot, cards);
    const writerCard = this.getCreativeTypeCardInSlot("writer", slot, cards);

    if (comicCard && artistCard && writerCard) {
      return (
        comicCard.genre === artistCard.genre &&
        comicCard.genre === writerCard.genre &&
        artistCard.displayValue !== writerCard.displayValue
      );
    }

    return false;
  }

  /**
   * Checks if player can can train both creatives with one action.
   *
   * Can double train if:
   * - Both creatives match the genre of the comic
   * - The creatives have the same values
   * - That value is less than 3
   *
   * @param slot - the slot of the comic on the player mat
   * @param cards - the cards on the player mat
   *
   * @returns boolean - true if the player can double train
   */
  canDoubleTrain(slot: number, cards: any[]): boolean {
    const comicCard = this.getComicCardInSlot(slot, cards);
    const artistCard = this.getCreativeTypeCardInSlot("artist", slot, cards);
    const writerCard = this.getCreativeTypeCardInSlot("writer", slot, cards);

    if (comicCard && artistCard && writerCard) {
      return (
        comicCard.genre === artistCard.genre &&
        comicCard.genre === writerCard.genre &&
        artistCard.displayValue === writerCard.displayValue &&
        artistCard.displayValue < 3
      );
    }

    return false;
  }

  /**
   * Check if the player can train a creative
   *
   * A creative can be trained if:
   * - Exactly one creative matches the genre of the comic
   * - The matching creative has a value less than 3
   *
   * @param slot
   * @param cards
   * @returns
   */
  canTrain(slot: number, cards: any[]): boolean {
    const comicCard = this.getComicCardInSlot(slot, cards);
    const artistCard = this.getCreativeTypeCardInSlot("artist", slot, cards);
    const writerCard = this.getCreativeTypeCardInSlot("writer", slot, cards);

    if (comicCard && artistCard && writerCard) {
      return (
        (comicCard.genre === artistCard.genre &&
          artistCard.displayValue < 3 &&
          comicCard.genre !== writerCard.genre) ||
        (comicCard.genre === writerCard.genre &&
          writerCard.displayValue < 3 &&
          comicCard.genre !== artistCard.genre)
      );
    }

    return false;
  }

  createActionColumn(slot: number, cards: any[], playerMoney: number): void {
    const increaseActionColumn =
      "<div id='aoc-increase-action-column-" +
      slot +
      "' class='aoc-increase-action-column'></div>";
    dojo.place(increaseActionColumn, "aoc-improve-creatives-comics");
    const increasableComicDiv =
      "<div id='aoc-increasable-comic-" +
      slot +
      "' class='aoc-increasable-comic-slot'></div>";
    dojo.place(increasableComicDiv, "aoc-increase-action-column-" + slot);

    // Add the comic card to the slot
    this.createComicCardInColumn(slot, cards);

    // Add the artist to the slot
    this.createCreativeCardInColumn("artist", slot, cards);

    // Add the writer to the slot
    this.createCreativeCardInColumn("writer", slot, cards);

    // Add the action buttons to the slot
    this.createColumnActionButtions(slot, cards, playerMoney);
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
      dojo.place(comicCardDiv, "aoc-increasable-comic-" + slot);
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
      dojo.place(creativeCardDiv, "aoc-increasable-comic-" + slot);
    }
  }

  createColumnActionButtions(
    slot: number,
    cards: any[],
    playerMoney: number
  ): void {
    // Create button if creative can learn
    if (this.canLearn(slot, cards)) {
      this.createLearnButton(slot, cards, playerMoney);
    } else if (this.canDoubleTrain(slot, cards)) {
    } else if (this.canTrain(slot, cards)) {
      this.createTrainButton(slot, cards, playerMoney);
    }
  }

  createEndActionButton(playerId: number): void {
    const endActionButtonDiv =
      "<a id='aoc-finish-increase-" +
      playerId +
      "' class='action-button bgabutton bgabutton_blue aoc-button'>" +
      _("End increasing creatives") +
      "</a>";
    dojo.place(endActionButtonDiv, "aoc-improve-creatives-button-container");
    this.connections["aoc-finish-increase-" + playerId] = dojo.connect(
      dojo.byId("aoc-finish-increase-" + playerId),
      "onclick",
      this,
      () => {
        this.finishIncreaseCreatives(playerId);
      }
    );
  }

  /**
   * Create the buttons if a double train is possible
   *
   * @param slot
   * @param cards
   * @param playerMoney
   */
  createDoubleTrainButtons(
    slot: number,
    cards: any[],
    playerMoney: number
  ): void {
    // Get one creative
    const artistCard = this.getCreativeTypeCardInSlot("artist", slot, cards);

    // Determine the cost of training
    const trainingCost = artistCard.displayValue + 1;

    // Determine cost of double training
    const doubleTrainingCost = trainingCost * 2;

    // Create train writer button
    const trainWriterButtonDiv =
      "<a id='aoc-train-writer-" +
      slot +
      "' class='action-button bgabutton bgabutton_blue aoc-button'>" +
      _("Train (Writer) - $" + trainingCost) +
      "</a>";
    dojo.place(trainWriterButtonDiv, "aoc-increase-action-column-" + slot);

    if (playerMoney < trainingCost) {
      dojo.addClass("aoc-train-writer-" + slot, "aoc-button-disabled");
    }

    // Create train artist button
    const trainArtistButtonDiv =
      "<a id='aoc-train-artist-" +
      slot +
      "' class='action-button bgabutton bgabutton_blue aoc-button'>" +
      _("Train (Artist) - $" + trainingCost) +
      "</a>";
    dojo.place(trainArtistButtonDiv, "aoc-increase-action-column-" + slot);

    if (playerMoney < trainingCost) {
      dojo.addClass("aoc-train-artist-" + slot, "aoc-button-disabled");
    }

    // Create train both button
    const trainBothButtonDiv =
      "<a id='aoc-train-both-" +
      slot +
      "' class='action-button bgabutton bgabutton_blue aoc-button'>" +
      _("Train (Both) - $" + doubleTrainingCost) +
      "</a>";
    dojo.place(trainBothButtonDiv, "aoc-increase-action-column-" + slot);

    if (playerMoney < doubleTrainingCost) {
      dojo.addClass("aoc-train-both-" + slot, "aoc-button-disabled");
    }
  }

  /**
   * Create the learn button for a comic
   *
   * @param slot
   * @param cards
   * @param playerMoney
   */
  createLearnButton(slot: number, cards: any[], playerMoney: number): void {
    // Get the creatives
    const artistCard = this.getCreativeTypeCardInSlot("artist", slot, cards);
    const writerCard = this.getCreativeTypeCardInSlot("writer", slot, cards);

    // Determine creative with lower value
    const lowerValueCreative =
      artistCard.displayValue < writerCard.displayValue
        ? artistCard
        : writerCard;

    // Determine the cost of learning
    const learningCost = 1;

    // Create the learn button
    const learnButtonDiv =
      "<a id='aoc-learn-" +
      slot +
      "' class='action-button bgabutton bgabutton_blue aoc-button'>" +
      _("Learn (" + lowerValueCreative.type + ") - $" + learningCost) +
      "</a>";
    dojo.place(learnButtonDiv, "aoc-increase-action-column-" + slot);

    // Disable the button if the player cannot afford it - should never happen but just in case
    if (playerMoney < learningCost) {
      dojo.addClass("aoc-learn-" + slot, "aoc-button-disabled");
    }
  }

  /**
   * Create the train button for a comic
   * @param slot
   * @param cards
   * @param playerMoney
   */
  createTrainButton(slot: number, cards: any[], playerMoney): void {
    // Get the creatives
    const comicCard = this.getComicCardInSlot(slot, cards);
    const artistCard = this.getCreativeTypeCardInSlot("artist", slot, cards);
    const writerCard = this.getCreativeTypeCardInSlot("writer", slot, cards);

    // Get the creative with the matching genre
    const matchingCreative =
      comicCard.genre === artistCard.genre ? artistCard : writerCard;

    // Determine the cost of training
    const trainingCost = matchingCreative.displayValue + 1;

    // Create the train button
    const trainButtonDiv =
      "<a id='aoc-train-" +
      slot +
      "' class='action-button bgabutton bgabutton_blue aoc-button'>" +
      _("Train (" + matchingCreative.type + ") - $" + trainingCost) +
      "</a>";
    dojo.place(trainButtonDiv, "aoc-increase-action-column-" + slot);

    // Disable the button if the player cannot afford it
    if (playerMoney < trainingCost) {
      dojo.addClass("aoc-train-" + slot, "aoc-button-disabled");
    }
  }

  finishIncreaseCreatives(playerId: number): void {
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_END_INCREASE_CREATIVES, {
      playerId: playerId,
    });
    this.onLeavingState();
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
}
