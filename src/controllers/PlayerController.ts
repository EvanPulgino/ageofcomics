/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PlayerController.ts
 *
 * Handles player logic on front-end
 *
 */

class PlayerController {
  ui: any;
  playerCounter: any;
  actions: any;

  constructor(ui: any) {
    this.ui = ui;
    this.actions = [];
    this.actions[1] = "hire";
    this.actions[2] = "develop";
    this.actions[3] = "ideas";
    this.actions[4] = "print";
    this.actions[5] = "royalties";
    this.actions[6] = "sales";
  }

  /**
   * Set up players
   * @param {object} playerData - current player data used to initialize UI
   */
  setupPlayers(playerData: any): void {
    this.playerCounter = {};
    for (var key in playerData) {
      this.createPlayerOrderToken(playerData[key]);
      this.createPlayerAgent(playerData[key]);
      this.sortAgents();
      this.createPlayerCubes(playerData[key]);
      this.createPlayerPanel(playerData[key]);
      this.createPlayerCounters(playerData[key]);
      this.createHandIconHoverEvents(playerData[key]);
    }
  }

  /**
   * Adjust a player's hand counter by a given amount
   *
   * @param player - player to adjust hand counter for
   * @param amount - amount to adjust hand counter by
   */
  adjustHand(player: any, amount: number): void {
    this.updatePlayerCounter(player.id, "hand", amount);
  }

  /**
   * Adjust a player's idea counters by a given amount
   *
   * @param player - player to adjust idea counter for
   * @param genre - genre of idea to adjust
   * @param amount - amount to adjust idea counter by
   */
  adjustIdeas(player: any, genre: string, amount: number): void {
    this.updatePlayerCounter(player.id, genre, amount);
  }

  /**
   * Adjust a player's money counter by a given amount
   *
   * @param player - player to adjust money counter for
   * @param amount - amount to adjust money counter by
   */
  adjustMoney(player: any, amount: number): void {
    this.updatePlayerCounter(player.id, "money", amount);
  }

  /**
   * Adjust a player's income counter by a given amount
   *
   * @param player - player to adjust income counter for
   * @param amount - amount to adjust income counter by
   */
  adjustIncome(player: any, amount: number): void {
    this.updatePlayerCounter(player.id, "income", amount);
  }

  /**
   * Adjust a player's point counter by a given amount
   *
   * @param player - player to adjust point counter for
   * @param amount - amount to adjust point counter by
   */
  adjustPoints(player: any, amount: number): void {
    this.updatePlayerCounter(player.id, "point", amount);
    this.ui.scoreCtrl[player.id].incValue(amount);
  }

  /**
   * Adjust a player's ticket counter by a given amount
   *
   * @param player - player to adjust ticket counter for
   * @param amount - amount to adjust ticket counter by
   */
  adjustTickets(player: any, amount: number): void {
    this.updatePlayerCounter(player.id, "ticket", amount);
  }

  /**
   * Show floating player hand when hovering over hand icon
   *
   * @param player
   */
  createHandIconHoverEvents(player: any): void {
    dojo.connect(
      $("aoc-player-hand-supply-" + player.id),
      "onmouseenter",
      this.ui,
      () => {
        if (this.ui.player_id != player.id) {
          dojo.toggleClass(
            "aoc-floating-hand-wrapper-" + player.id,
            "aoc-hidden"
          );
        }
      }
    );
    dojo.connect(
      $("aoc-player-panel-hand-" + player.id + "-supply"),
      "onmouseenter",
      this.ui,
      () => {
        if (this.ui.player_id != player.id) {
          dojo.toggleClass(
            "aoc-floating-hand-wrapper-" + player.id,
            "aoc-hidden"
          );
        }
      }
    );
    dojo.connect(
      $("aoc-player-hand-supply-" + player.id),
      "onmouseleave",
      this.ui,
      () => {
        if (this.ui.player_id != player.id) {
          dojo.toggleClass(
            "aoc-floating-hand-wrapper-" + player.id,
            "aoc-hidden"
          );
        }
      }
    );
    dojo.connect(
      $("aoc-player-panel-hand-" + player.id + "-supply"),
      "onmouseleave",
      this.ui,
      () => {
        if (this.ui.player_id != player.id) {
          dojo.toggleClass(
            "aoc-floating-hand-wrapper-" + player.id,
            "aoc-hidden"
          );
        }
      }
    );
  }

  /**
   * Create a player order token element
   *
   * @param player - player to create token for
   */
  createPlayerOrderToken(player: any): void {
    var playerOrderTokenDiv =
      '<div id="aoc-player-order-token' +
      player.id +
      '" class="aoc-player-order-token aoc-player-order-token-' +
      player.colorAsText +
      '"></div>';
    this.ui.createHtml(
      playerOrderTokenDiv,
      "aoc-player-order-space-" + player.turnOrder
    );
  }

  /**
   * Create a player sales agent element
   *
   * @param player - player to create sales agent for
   */
  createPlayerAgent(player: any): void {
    var playerAgentDiv =
      '<div id="aoc-agent-' +
      player.id +
      '" class="aoc-agent aoc-agent-' +
      player.colorAsText +
      '" arrived="' +
      player.agentArrived +
      '"></div>';
    this.ui.createHtml(
      playerAgentDiv,
      "aoc-map-agent-space-" + player.agentLocation
    );
  }

  /**
   * Create a player cube element
   *
   * @param player - player to create cube for
   */
  createPlayerCubes(player: any): void {
    this.createPlayerCubeOne(player);
    this.createPlayerCubeTwo(player);
    this.createPlayerCubeThree(player);
  }

  /**
   * Create a player cube one element
   *
   * @param player - player to create cube one for
   */
  createPlayerCubeOne(player: any): void {
    var cubeDiv =
      '<div id="aoc-player-cube-one-' +
      player.id +
      '" class="aoc-player-cube aoc-player-cube-' +
      player.colorAsText +
      '"></div>';
    if (player.cubeOneLocation == 0) {
      this.ui.createHtml(cubeDiv, "aoc-cube-one-space-" + player.id);
    } else {
      this.ui.createHtml(
        cubeDiv,
        "aoc-action-upgrade-" +
          this.actions[player.cubeOneLocation] +
          "-" +
          player.colorAsText
      );
    }
  }

  /**
   * Create a player cube two element
   *
   * @param player - player to create cube two for
   */
  createPlayerCubeTwo(player: any): void {
    var cubeDiv =
      '<div id="aoc-player-cube-two-' +
      player.id +
      '" class="aoc-player-cube aoc-player-cube-' +
      player.colorAsText +
      '"></div>';
    if (player.cubeTwoLocation == 0) {
      this.ui.createHtml(cubeDiv, "aoc-cube-two-space-" + player.id);
    } else {
      this.ui.createHtml(
        cubeDiv,
        "aoc-action-upgrade-" +
          this.actions[player.cubeTwoLocation] +
          "-" +
          player.colorAsText
      );
    }
  }

  /**
   * Create a player cube three element
   *
   * @param player - player to create cube three for
   */
  createPlayerCubeThree(player: any): void {
    var cubeDiv =
      '<div id="aoc-player-cube-three-' +
      player.id +
      '" class="aoc-player-cube aoc-player-cube-' +
      player.colorAsText +
      '"></div>';
    if (player.cubeThreeLocation == 0) {
      this.ui.createHtml(cubeDiv, "aoc-cube-three-space-" + player.id);
    } else {
      this.ui.createHtml(
        cubeDiv,
        "aoc-action-upgrade-" +
          this.actions[player.cubeThreeLocation] +
          "-" +
          player.colorAsText
      );
    }
  }

  /**
   * Create a player panel element
   *
   * @param player - player to create panel for
   */
  createPlayerPanel(player: any): void {
    var playerPanelDiv =
      '<div id="aoc-player-panel-' +
      player.id +
      '" class="aoc-player-panel">' +
      '<div id="aoc-player-panel-mastery-' +
      player.id +
      '" class="aoc-player-panel-row aoc-player-panel-mastery"></div>' +
      '<div id="aoc-player-panel-ideas-1-' +
      player.id +
      '" class="aoc-player-panel-row">' +
      this.createPlayerPanelIdeaSupplyDiv(player, "crime") +
      this.createPlayerPanelIdeaSupplyDiv(player, "horror") +
      this.createPlayerPanelIdeaSupplyDiv(player, "romance") +
      "</div>" +
      '<div id="aoc-player-panel-ideas-2-' +
      player.id +
      '" class="aoc-player-panel-row">' +
      this.createPlayerPanelIdeaSupplyDiv(player, "scifi") +
      this.createPlayerPanelIdeaSupplyDiv(player, "superhero") +
      this.createPlayerPanelIdeaSupplyDiv(player, "western") +
      "</div>" +
      '<div id="aoc-player-panel-other-1' +
      player.id +
      '" class="aoc-player-panel-row">' +
      this.createPlayerPanelOtherSupplyDiv(player, "money") +
      this.createPlayerPanelOtherSupplyDiv(player, "point") +
      this.createPlayerPanelOtherSupplyDiv(player, "income") +
      "</div>" +
      '<div id="aoc-player-panel-other-2' +
      player.id +
      '" class="aoc-player-panel-row">' +
      this.createPlayerPanelOtherSupplyDiv(player, "hand") +
      this.createPlayerPanelOtherSupplyDiv(player, "tickets") +
      "</div>" +
      "</div>";
    this.ui.createHtml(playerPanelDiv, "player_board_" + player.id);
  }

  /**
   * Create a player panel idea supply element
   *
   * @param player - player to create idea supply for
   * @param genre - genre of idea supply to create
   * @returns - HTML element
   */
  createPlayerPanelIdeaSupplyDiv(player: any, genre: string): any {
    var ideaSupplyDiv =
      '<div id="aoc-player-panel-' +
      genre +
      "-supply-" +
      player.id +
      '" class="aoc-player-panel-supply aoc-player-panel-idea-supply"><span id="aoc-player-panel-' +
      genre +
      "-count-" +
      player.id +
      '" class="aoc-player-panel-supply-count aoc-squada"></span><span id="aoc-player-panel-' +
      genre +
      "-" +
      player.id +
      '" class="aoc-idea-token aoc-idea-token-' +
      genre +
      '"></span></div>';
    return ideaSupplyDiv;
  }

  /**
   * Create a player panel other supply element (money, points, income)
   *
   * @param player - player to create other supply for
   * @param supply - other supply to create
   * @returns - HTML element
   */
  createPlayerPanelOtherSupplyDiv(player: any, supply: string): any {
    var otherSupplyDiv;
    switch (supply) {
      case "hand":
        otherSupplyDiv =
          '<div id="aoc-player-panel-hand-' +
          player.id +
          '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-hand-count-' +
          player.id +
          '" class="aoc-player-panel-supply-count aoc-squada"></span><i id="aoc-player-panel-hand-' +
          player.id +
          '" class="aoc-hand-icon"></i></div>';
        break;
      case "money":
        otherSupplyDiv =
          '<div id="aoc-player-panel-money-' +
          player.id +
          '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-money-count-' +
          player.id +
          '" class="aoc-player-panel-supply-count aoc-squada"></span><i id="aoc-player-panel-money-' +
          player.id +
          '" class="aoc-round-token aoc-token-coin"></i></div>';
        break;
      case "point":
        otherSupplyDiv =
          '<div id="aoc-player-panel-point-' +
          player.id +
          '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-point-count-' +
          player.id +
          '" class="aoc-player-panel-supply-count aoc-squada"></span><span id="aoc-player-panel-points-' +
          player.id +
          '" class="aoc-round-token aoc-token-point"></span></div>';
        break;
      case "income":
        otherSupplyDiv =
          '<div id="aoc-player-panel-income-' +
          player.id +
          '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-income-count-' +
          player.id +
          '" class="aoc-player-panel-income-count aoc-squada"></span><i id="aoc-player-panel-income-' +
          player.id +
          '" class="aoc-player-panel-icon-size fa6 fa6-solid fa6-money-bill-trend-up"></i></div>';
        break;
      case "tickets":
        otherSupplyDiv =
          '<div id="aoc-player-panel-ticket-' +
          player.id +
          '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-ticket-count-' +
          player.id +
          '" class="aoc-player-panel-ticket-count aoc-squada"></span><i id="aoc-player-panel-ticket-' +
          player.id +
          '" class="aoc-ticket-icon"></i></div>';
        break;
    }
    return otherSupplyDiv;
  }

  /**
   * Create all player counters
   *
   * @param player - player to create counters for
   */
  createPlayerCounters(player: any): void {
    this.playerCounter[player.id] = {};
    this.createPlayerCounter(player, "crime", player.crimeIdeas, true);
    this.createPlayerCounter(player, "horror", player.horrorIdeas, true);
    this.createPlayerCounter(player, "romance", player.romanceIdeas, true);
    this.createPlayerCounter(player, "scifi", player.scifiIdeas, true);
    this.createPlayerCounter(player, "superhero", player.superheroIdeas, true);
    this.createPlayerCounter(player, "western", player.westernIdeas, true);
    this.createPlayerCounter(player, "money", player.money, true);
    this.createPlayerCounter(player, "point", player.score, true);
    this.createPlayerCounter(player, "income", player.income, true);
    this.createPlayerCounter(player, "hand", player.handSize, true);
    this.createPlayerCounter(player, "ticket", player.tickets, true);
  }

  /**
   * Create and initialize a player counter
   *
   * @param player - player to create counter for
   * @param counter - counter to create
   * @param initialValue - initial value of counter
   * @param onPanel - if true the counter is on the player panel as well as the mat (default false)
   */
  createPlayerCounter(
    player: any,
    counter: string,
    initialValue: number,
    onPanel: boolean = false
  ): void {
    var counterKey = counter;

    this.playerCounter[player.id][counterKey] = new ebg.counter();
    this.playerCounter[player.id][counterKey].create(
      "aoc-player-" + counter + "-count-" + player.id
    );
    this.playerCounter[player.id][counterKey].setValue(initialValue);

    if (onPanel) {
      var counterPanel = "panel-" + counter;

      this.playerCounter[player.id][counterPanel] = new ebg.counter();
      this.playerCounter[player.id][counterPanel].create(
        "aoc-player-" + counterPanel + "-count-" + player.id
      );
      this.playerCounter[player.id][counterPanel].setValue(initialValue);
    }
  }

  createPrintedComicOverlays(
    playerData: any,
    cards: any[],
    miniComics: any[]
  ): void {
    for (var key in playerData) {
      var player = playerData[key];
      const numberOfPrintedSlots = this.getNumberOfPrintedSlots(
        player.id,
        cards
      );
      for (var i = 1; i <= numberOfPrintedSlots; i++) {
        this.createPrintedComicOverlay(player, i, cards, miniComics);
      }
    }
  }

  createPrintedComicOverlay(
    player: any,
    slot: number,
    cards: any[],
    miniComics: any[]
  ): void {
    const artistCard = cards.find(
      (card) =>
        card.playerId === player.id &&
        card.location === globalThis.LOCATION_PLAYER_MAT &&
        card.locationArg === slot &&
        card.type === "artist"
    );
    const writerCard = cards.find(
      (card) =>
        card.playerId === player.id &&
        card.location === globalThis.LOCATION_PLAYER_MAT &&
        card.locationArg === slot &&
        card.type === "writer"
    );
    const comicCard = cards.find(
      (card) =>
        card.playerId === player.id &&
        card.location === globalThis.LOCATION_PLAYER_MAT &&
        card.locationArg === slot &&
        (card.type === "comic" || card.type === "ripoff")
    );
    const miniComic = miniComics.find(
      (miniComic) => miniComic.id === comicCard.id
    );

    // Calculate the comic value, fans, and income
    const comicValue = artistCard.displayValue + writerCard.displayValue;
    const comicFans = miniComic.fans;
    const comicIncome = globalThis.CHART_INCOME_LEVELS[comicFans];

    // Create the overlay div
    const overlayDiv = `<div id="aoc-printed-comic-overlay-${player.id}-${slot}" class="aoc-printed-comic-overlay aoc-squada"></div>`;
    this.ui.createHtml(overlayDiv, "aoc-comic-slot-" + slot + "-" + player.id);

    // Create the comic value div
    const comicValueString = this.ui.clienttranslate_string("Value") + ": ";
    const comicValueDiv = `<div id="aoc-printed-comic-value-${player.id}-${slot}" class="aoc-printed-comic-value">${comicValueString}</div>`;
    this.ui.createHtml(
      comicValueDiv,
      "aoc-printed-comic-overlay-" + player.id + "-" + slot
    );

    // Create value counter span and counter
    const comicValueCounterSpan = `<span id="aoc-player-slot-${slot}-count-${player.id}" class="aoc-printed-comic-value-counter"></span>`;
    this.ui.createHtml(
      comicValueCounterSpan,
      "aoc-printed-comic-value-" + player.id + "-" + slot
    );
    this.createPlayerCounter(player, "slot-" + slot, comicValue);

    // Create the comic fans div
    const comicFansString = this.ui.clienttranslate_string("Fans") + ": ";
    const comicFansDiv = `<div id="aoc-printed-comic-fans-${player.id}-${slot}" class="aoc-printed-comic-fans">${comicFansString}</div>`;
    this.ui.createHtml(
      comicFansDiv,
      "aoc-printed-comic-overlay-" + player.id + "-" + slot
    );

    // Create fans counter span and counter
    const comicFansCounterSpan = `<span id="aoc-player-fans-${slot}-count-${player.id}" class="aoc-printed-comic-fans-counter"></span>`;
    this.ui.createHtml(
      comicFansCounterSpan,
      "aoc-printed-comic-fans-" + player.id + "-" + slot
    );
    this.createPlayerCounter(player, "fans-" + slot, comicFans);

    // Create the comic income div
    const comicIncomeString = this.ui.clienttranslate_string("Income") + ": ";
    const comicIncomeDiv = `<div id="aoc-printed-comic-income-${player.id}-${slot}" class="aoc-printed-comic-income">${comicIncomeString}</div>`;
    this.ui.createHtml(
      comicIncomeDiv,
      "aoc-printed-comic-overlay-" + player.id + "-" + slot
    );

    // Create income counter span and counter
    const comicIncomeCounterSpan = `<span id="aoc-player-income-${slot}-count-${player.id}" class="aoc-printed-comic-income-counter"></span>`;
    this.ui.createHtml(
      comicIncomeCounterSpan,
      "aoc-printed-comic-income-" + player.id + "-" + slot
    );
    this.createPlayerCounter(player, "income-" + slot, comicIncome);
  }

  /**
   * Get the number of printed slots for a player
   *
   * @param playerId
   * @param cards
   * @returns
   */
  getNumberOfPrintedSlots(playerId: number, cards: any[]): number {
    const printedCards = cards.filter(
      (card) =>
        card.playerId === playerId &&
        card.location == globalThis.LOCATION_PLAYER_MAT
    );
    var maxLocationArg = 0;
    printedCards.forEach((card) => {
      if (card.locationArg > maxLocationArg) {
        maxLocationArg = card.locationArg;
      }
    });
    return maxLocationArg;
  }

  /**
   * Move a player sales agent to a new space on the map
   *
   * @param player - player to move sales agent for
   * @param space - space to move sales agent to
   * @param arrived - the turn the agent arrived on the space
   */
  moveSalesAgent(player: any, space: number, arrived: number): void {
    const agentDiv = "aoc-agent-" + player.id;
    const targetDiv = "aoc-map-agent-space-" + space;

    const animation = this.ui.slideToObject(agentDiv, targetDiv);
    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(agentDiv, "style");
      dojo.place(agentDiv, targetDiv);
      dojo.setAttr(agentDiv, "arrived", arrived);
      this.sortAgentsOnSpace(space);
    });
    animation.play();
  }

  /**
   * Move a player upgrade cube to a new location
   *
   * @param player - player to move cube for
   * @param cube - cube to move
   * @param action - action to move cube to
   */
  moveUpgradeCube(player: any, cube: number, action: number): void {
    var numberText = "";
    if (cube == 1) {
      numberText = "one";
    }
    if (cube == 2) {
      numberText = "two";
    }
    if (cube == 3) {
      numberText = "three";
    }
    const actionText = this.actions[action];
    const cubeDiv = "aoc-player-cube-" + numberText + "-" + player.id;
    const targetDiv =
      "aoc-action-upgrade-" + actionText + "-" + player.colorAsText;

    const animation = this.ui.slideToObject(cubeDiv, targetDiv);
    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(cubeDiv, "style");
      dojo.place(cubeDiv, targetDiv);
    });
    animation.play();
  }

  /**
   * Sort all agents on the map by the turn they arrived
   */
  sortAgents(): void {
    const agentSpaces = globalThis.SALES_AGENT_CONNECTIONS;
    for (const space of Object.keys(agentSpaces)) {
      this.sortAgentsOnSpace(parseInt(space));
    }
  }

  /**
   * Sort agents on a given space by the turn they arrived
   *
   * @param space
   */
  sortAgentsOnSpace(space: number): void {
    const agentSpaceDivId = `aoc-map-agent-space-${space}`;
    const agentSpaceContainer = dojo.byId(agentSpaceDivId);
    const agents: HTMLElement[] = agentSpaceContainer.children;

    const sortedAgents = Array.from(agents).sort((a, b) => {
      const aArrived = dojo.getAttr(a, "arrived");
      const bArrived = dojo.getAttr(b, "arrived");
      return aArrived - bArrived;
    });

    for (let i = 0; i < sortedAgents.length; i++) {
      dojo.place(sortedAgents[i], agentSpaceDivId);
    }
  }

  /**
   * Update the value of a player counter
   *
   * @param playerId - player to update counter for
   * @param counter - counter to update
   * @param value - value to adjust counter by
   */
  updatePlayerCounter(playerId: any, counter: string, value: number): void {
    var counterKey = counter;
    var counterPanel = "panel-" + counter;
    this.playerCounter[playerId][counterKey].incValue(value);
    this.playerCounter[playerId][counterPanel].incValue(value);
  }

  /**
   * Moves a player's order token to a new turn order space
   *
   * @param player
   */
  updatePlayerOrder(player: any): void {
    const playerOrderTokenDiv = `aoc-player-order-token${player.id}`;
    const turnOrderSpaceDiv = `aoc-player-order-space-${player.turnOrder}`;

    const animation = this.ui.slideToObject(
      playerOrderTokenDiv,
      turnOrderSpaceDiv
    );
    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(playerOrderTokenDiv, "style");
      dojo.place(playerOrderTokenDiv, turnOrderSpaceDiv);
    });
    animation.play();
  }
}
