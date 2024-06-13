/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformSales.ts
 *
 * Age of Comics perform sales state
 *
 * State vars:
 *  game: game object reference
 *
 */

class PerformSales implements State {
  game: any;
  connections: any;
  salesAgentConnections: any;
  salesOrderConnections: any;
  flipsCounter: any;
  collectsCounter: any;
  remainingFlipActions: number;
  remainingCollectActions: number;

  constructor(game: any) {
    this.game = game;
    this.connections = {};
    this.salesAgentConnections = {};
    this.salesOrderConnections = {};
    this.flipsCounter = {};
    this.collectsCounter = {};
    this.remainingFlipActions = 0;
    this.remainingCollectActions = 0;
  }

  onEnteringState(stateArgs: any): void {
    this.remainingCollectActions = stateArgs.args.remainingCollectActions;
    this.remainingFlipActions = stateArgs.args.remainingFlipActions;

    // Create the remaining actions div
    this.createRemainingActionsDiv();

    this.flipsCounter = new ebg.counter();
    this.flipsCounter.create("aoc-remaining-flips");
    this.flipsCounter.setValue(stateArgs.args.remainingFlipActions);

    this.collectsCounter = new ebg.counter();
    this.collectsCounter.create("aoc-remaining-collects");
    this.collectsCounter.setValue(stateArgs.args.remainingCollectActions);

    if (stateArgs.args.hasWalked) {
      this.addWalkNotAllowed();
    }

    if (stateArgs.args.playerMoney < 2) {
      this.addTaxiNotAllowed();
      this.addSharedSpaceNotAllowed();
    }

    if (stateArgs.isCurrentPlayerActive) {
      this.createFlipOrCollectCounterDiv();

      this.salesAgentConnections = globalThis.SALES_AGENT_CONNECTIONS;
      this.salesOrderConnections =
        globalThis.SALES_ORDER_CONNECTIONS[stateArgs.args.playerCount];

      // Highlight the spaces the sales agent can move to
      // The player can only move if they haven't used their free walk action
      // or they have enough money to pay to take a cab
      if (!stateArgs.args.hasWalked || stateArgs.args.playerMoney >= 2) {
        this.highlightAdjacentSalesAgentSpaces(
          stateArgs.args.salesAgentLocation
        );
      }

      const salesAgentsOnSpace = this.getSalesAgentsOnSpace(
        stateArgs.args.salesAgentLocation
      );

      const canAffordToInteract =
        salesAgentsOnSpace.length == 1 || stateArgs.args.playerMoney >= 2;

      // Highlight the sales orders the player can interact with as long as
      // player has at least 1 collect or flip action remaining
      if (
        (canAffordToInteract && stateArgs.args.remainingCollectActions > 0) ||
        stateArgs.args.remainingFlipActions > 0
      ) {
        this.highlightConnectedSalesOrderSpaces(
          stateArgs.args.salesAgentLocation,
          stateArgs.args.remainingCollectActions
        );
      }
    }
  }
  onLeavingState(): void {}

  onUpdateActionButtons(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      gameui.addActionButton("aoc-end-sales", _("End action"), () => {
        this.endSales();
      });

      dojo.addClass("aoc-end-sales", "aoc-button");

      gameui.addActionButton(
        "aoc-use-ticket",
        _("Use Super-transport Ticket"),
        () => {
          this.useTicket();
        }
      );

      dojo.addClass("aoc-use-ticket", "aoc-button-disabled");
      dojo.addClass("aoc-use-ticket", "aoc-button");
    }
  }

  addSharedSpaceNotAllowed() {
    if (dojo.byId("aoc-shared-space-not-allowed-icon")) return;

    const sharedSpaceNotAllowedIconDiv =
      "<div id='aoc-shared-space-not-allowed-icon' class='aoc-not-allowed aoc-shared-space-not-allowed'></div>";
    this.game.createHtml(sharedSpaceNotAllowedIconDiv, "aoc-board");
  }

  addTaxiNotAllowed() {
    if (dojo.byId("aoc-taxi-not-allowed-icon")) return;

    const taxiNotAllowedIconDiv =
      "<div id='aoc-taxi-not-allowed-icon' class='aoc-not-allowed aoc-taxi-not-allowed'></div>";
    this.game.createHtml(taxiNotAllowedIconDiv, "aoc-board");
  }

  addWalkNotAllowed() {
    if (dojo.byId("aoc-walk-not-allowed-icon")) return;

    const walkNotAllowedIconDiv =
      "<div id='aoc-walk-not-allowed-icon' class='aoc-not-allowed aoc-walk-not-allowed'></div>";
    this.game.createHtml(walkNotAllowedIconDiv, "aoc-board");
  }

  cancelSalesOrderAction(): void {
    this.resetActionPanel();
  }

  clickSalesOrder(salesOrderTileId: string): void {
    this.resetActionPanel();

    const salesOrderTile = dojo.byId(salesOrderTileId);
    const tileId = salesOrderTileId.split("-")[2];
    var selectedSalesOrderTile = dojo.clone(salesOrderTile);
    dojo.attr(selectedSalesOrderTile, "id", "aoc-selected-sales-order");
    dojo.attr(selectedSalesOrderTile, "sales-order-id", tileId);
    dojo.removeClass(selectedSalesOrderTile, "aoc-clickable");
    dojo.place(selectedSalesOrderTile, "aoc-selected-sales-order-container");

    dojo.addClass(salesOrderTile, "aoc-selected");
    dojo.removeClass("aoc-flip-or-collect-counter", "aoc-hidden");

    if (this.remainingFlipActions > 0 && this.isTileFacedown(salesOrderTile)) {
      dojo.removeClass("aoc-flip-button", "aoc-button-disabled");
    }
    if (this.remainingCollectActions > 0) {
      dojo.removeClass("aoc-collect-button", "aoc-button-disabled");
    }
  }

  collectSalesOrder(): void {
    const salesOrderId = dojo
      .byId("aoc-selected-sales-order")
      .getAttribute("sales-order-id");

    this.resetUX();
    this.collectsCounter.incValue(-1);

    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_COLLECT_SALES_ORDER, {
      salesOrderId: salesOrderId,
    });
  }

  /**
   * Create the div that tracks remaining actions
   */
  createRemainingActionsDiv(): void {
    const actionsDiv = document.getElementById("aoc-remaining-actions");

    // If the div already exists, return
    if (actionsDiv) return;

    const remainingActionsDiv =
      "<div id='aoc-remaining-actions' class='aoc-action-panel-row'></div>";
    this.game.createHtml(remainingActionsDiv, "page-title");

    const remainingFlipsContainerDiv = `<div id='aoc-remaining-flips-container' class='aoc-player-panel-supply aoc-player-panel-other-supply'><span id='aoc-remaining-flips' class='aoc-player-panel-supply-count aoc-squada' style="padding-right: 5px !important"></span><span id='aoc-remaining-flips-icon' class='aoc-sales-action-icon aoc-sales-action-flip'></span></div>`;
    this.game.createHtml(remainingFlipsContainerDiv, "aoc-remaining-actions");

    const remainingCollectsContainerDiv = `<div id='aoc-remaining-collects-container' class='aoc-player-panel-supply aoc-player-panel-other-supply'><span id='aoc-remaining-collects' class='aoc-player-panel-supply-count aoc-squada'></span><span id='aoc-remaining-collects-icon' class='aoc-sales-action-icon aoc-sales-action-collect'></span></div>`;
    this.game.createHtml(
      remainingCollectsContainerDiv,
      "aoc-remaining-actions"
    );
  }

  createFlipOrCollectCounterDiv(): void {
    const interactionDiv = document.getElementById(
      "aoc-flip-or-collect-counter"
    );

    // If the div already exists, return
    if (interactionDiv) {
      this.connections["flipSalesOrder"] = dojo.connect(
        dojo.byId("aoc-flip-button"),
        "onclick",
        dojo.hitch(this, this.flipSalesOrder)
      );
      this.connections["collectSalesOrder"] = dojo.connect(
        dojo.byId("aoc-collect-button"),
        "onclick",
        dojo.hitch(this, this.collectSalesOrder)
      );
      this.connections["cancelSalesOrderAction"] = dojo.connect(
        dojo.byId("aoc-cancel-button"),
        "onclick",
        dojo.hitch(this, this.cancelSalesOrderAction)
      );
      return;
    }

    const flipOrCollectCounterDiv =
      "<div id='aoc-flip-or-collect-counter' class='aoc-action-panel-row'></div>";
    this.game.createHtml(flipOrCollectCounterDiv, "page-title");

    const selectedSalesOrderContainerDiv =
      "<div id='aoc-selected-sales-order-container' class='aoc-sales-order-selection-container'></div>";
    this.game.createHtml(
      selectedSalesOrderContainerDiv,
      "aoc-flip-or-collect-counter"
    );

    const flipButtonDiv =
      "<a id='aoc-flip-button' class='action-button bgabutton bgabutton_blue aoc-button aoc-button-disabled'>" +
      _("Flip") +
      "</a>";
    this.game.createHtml(flipButtonDiv, "aoc-flip-or-collect-counter");

    this.connections["flipSalesOrder"] = dojo.connect(
      dojo.byId("aoc-flip-button"),
      "onclick",
      dojo.hitch(this, this.flipSalesOrder)
    );

    const collectButtonDiv =
      "<a id='aoc-collect-button' class='action-button bgabutton bgabutton_blue aoc-button aoc-button-disabled'>" +
      _("Collect") +
      "</a>";
    this.game.createHtml(collectButtonDiv, "aoc-flip-or-collect-counter");

    this.connections["collectSalesOrder"] = dojo.connect(
      dojo.byId("aoc-collect-button"),
      "onclick",
      dojo.hitch(this, this.collectSalesOrder)
    );

    const cancelButtonDiv =
      "<a id='aoc-cancel-button' class='action-button bgabutton bgabutton_blue aoc-button'>" +
      _("Cancel") +
      "</a>";
    this.game.createHtml(cancelButtonDiv, "aoc-flip-or-collect-counter");

    this.connections["cancelSalesOrderAction"] = dojo.connect(
      dojo.byId("aoc-cancel-button"),
      "onclick",
      dojo.hitch(this, this.cancelSalesOrderAction)
    );
  }

  /**
   * End the sales phase
   */
  endSales(): void {
    console.log("Ending sales phase");
  }

  flipSalesOrder(): void {
    const salesOrderId = dojo
      .byId("aoc-selected-sales-order")
      .getAttribute("sales-order-id");

    this.resetUX();
    this.flipsCounter.incValue(-1);

    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_FLIP_SALES_ORDER, {
      salesOrderId: salesOrderId,
    });
  }

  getConnectedSalesOrderTiles(agentSpace: number): any[] {
    const connectedSpaces = this.salesOrderConnections[agentSpace];

    var salesOrderTiles = [];

    for (const space of connectedSpaces) {
      const spaceDivId = `aoc-map-order-space-${space}`;
      const spaceContainer = dojo.byId(spaceDivId);
      const salesOrderTile = spaceContainer.firstChild;
      if (salesOrderTile) {
        salesOrderTiles.push(salesOrderTile);
      }
    }

    return salesOrderTiles;
  }

  getSalesAgentsOnSpace(space: number): any[] {
    const agentSpaceDivId = `aoc-map-agent-space-${space}`;
    const agentSpaceContainer = dojo.byId(agentSpaceDivId);
    return agentSpaceContainer.children;
  }

  /**
   * Called when state is loaded.
   * Highlights and creates click listeners for spaces that the sales agent can move to.
   *
   * @param salesAgentLocation - the current location of the sales agent
   */
  highlightAdjacentSalesAgentSpaces(salesAgentLocation: number): void {
    const adjacentSpaces = this.salesAgentConnections[salesAgentLocation];

    for (const space of adjacentSpaces) {
      const divId = `aoc-map-agent-space-${space}`;
      dojo.addClass(divId, "aoc-clickable");
      this.connections[divId] = dojo.connect(
        dojo.byId(divId),
        "onclick",
        dojo.hitch(this, this.moveSalesAgentToSpace, space)
      );
    }
  }

  highlightConnectedSalesOrderSpaces(
    agentSpace: number,
    remainingCollectActions: number
  ): void {
    const salesOrderTiles = this.getConnectedSalesOrderTiles(agentSpace);

    for (const salesOrderTile of salesOrderTiles) {
      // If the tile is face up and the player has no remaining collect actions, skip it as it can't be flipped
      if (
        !this.isTileFacedown(salesOrderTile) &&
        remainingCollectActions === 0
      ) {
        continue;
      }
      if (
        salesOrderTile.parentElement.classList.contains(
          "aoc-player-sales-orders"
        )
      ) {
        continue;
      }
      dojo.addClass(salesOrderTile.id, "aoc-clickable");
      this.connections[salesOrderTile.id] = dojo.connect(
        salesOrderTile,
        "onclick",
        dojo.hitch(this, this.clickSalesOrder, salesOrderTile.id)
      );
    }
  }

  isTileFacedown(salesOrderTile: any): boolean {
    for (const divClass of salesOrderTile.classList) {
      if (divClass.includes("facedown")) {
        return true;
      }
    }
    return false;
  }

  moveSalesAgentToSpace(space: number): void {
    this.resetUX();
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_MOVE_SALES_AGENT, {
      space: space,
    });
  }

  resetActionPanel(): void {
    dojo.destroy("aoc-selected-sales-order");
    dojo.query(".aoc-selected").removeClass("aoc-selected");
    dojo.addClass("aoc-flip-button", "aoc-button-disabled");
    dojo.addClass("aoc-collect-button", "aoc-button-disabled");
  }

  resetUX(removeActionBanner: boolean = false): void {
    this.resetActionPanel();
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");
    dojo.query(".aoc-button").forEach((button) => {
      dojo.addClass(button, "aoc-button-disabled");
    });
    if (removeActionBanner) {
      dojo.destroy("aoc-remaining-actions");
    }
    for (const connection in this.connections) {
      dojo.disconnect(this.connections[connection]);
    }
    this.connections = {};
  }

  useTicket(): void {
    console.log("Using ticket");
  }
}
