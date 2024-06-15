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
  playerIdToPay: number;
  stateArgs: any;

  constructor(game: any) {
    this.game = game;
    this.connections = {};
    this.salesAgentConnections = {};
    this.salesOrderConnections = {};
    this.flipsCounter = {};
    this.collectsCounter = {};
    this.remainingFlipActions = 0;
    this.remainingCollectActions = 0;
    this.playerIdToPay = 0;
    this.stateArgs = {};
  }

  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      this.stateArgs = stateArgs;
      this.remainingCollectActions = stateArgs.args.remainingCollectActions;
      this.remainingFlipActions = stateArgs.args.remainingFlipActions;

      // Create the remaining actions div
      this.createRemainingActionsDiv();

      // Create the action counters
      this.createActionCounters();

      // If the player has walked, show that the walk action is not allowed
      if (stateArgs.args.hasWalked) {
        this.addWalkNotAllowed();
      }

      // If the player has less than 2 money, show that the taxi action and shared space actions are not allowed
      if (stateArgs.args.playerMoney < 2) {
        this.addTaxiNotAllowed();
        this.addSharedSpaceNotAllowed();
      }

      // Create the div that tracks the flip and collect actions
      this.createFlipOrCollectCounterDiv();

      // If the player has not already paid for the current space, determine who they need to pay
      if (stateArgs.args.paidForCurrentSpace === false) {
        this.determinePlayerToPayForSpace(
          stateArgs.args.salesAgentLocation,
          parseInt(stateArgs.active_player)
        );
      }

      // Get the sales agent and sales order connections
      this.salesAgentConnections = globalThis.SALES_AGENT_CONNECTIONS;
      this.salesOrderConnections =
        globalThis.SALES_ORDER_CONNECTIONS[stateArgs.args.playerCount];

      // Highlight the adjacent spaces the sales agent can move to
      // The player can only move if they haven't used their free walk action
      // or they have enough money to pay to take a cab
      if (!stateArgs.args.hasWalked || stateArgs.args.playerMoney >= 2) {
        this.highlightAdjacentSalesAgentSpaces(
          stateArgs.args.salesAgentLocation
        );
      }

      // Get the sales agents on the current space
      const salesAgentsOnSpace = this.getSalesAgentsOnSpace(
        stateArgs.args.salesAgentLocation
      );

      // Determine if the player can afford to interact with the sales orders
      // They can interact is no one else is on the space since it's free
      // They can interact if they have at least 2 money to pay for the action
      // They can interact if they have already paid for the current space
      const canAffordToInteract =
        salesAgentsOnSpace.length == 1 ||
        stateArgs.args.playerMoney >= 2 ||
        stateArgs.args.paidForCurrentSpace;

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
      // Add buttonto end the sales phase
      gameui.addActionButton("aoc-end-sales", _("End action"), () => {
        this.endSales();
      });
      dojo.addClass("aoc-end-sales", "aoc-button");

      // Add button to use the super-transport ticket
      gameui.addActionButton(
        "aoc-use-ticket",
        _("Use Super-transport Ticket"),
        () => {
          this.useTicket();
        }
      );
      dojo.addClass("aoc-use-ticket", "aoc-button");
      // Disable the button if the player has no tickets
      if (stateArgs.args.tickets === 0) {
        dojo.addClass("aoc-use-ticket", "aoc-button-disabled");
      }
    }
  }

  /**
   * Adds a red X icon to the board to indicate that the player cannot take actions on shared spaces
   *
   * @returns
   */
  addSharedSpaceNotAllowed() {
    if (dojo.byId("aoc-shared-space-not-allowed-icon")) return;

    const sharedSpaceNotAllowedIconDiv =
      "<div id='aoc-shared-space-not-allowed-icon' class='aoc-not-allowed aoc-shared-space-not-allowed'></div>";
    this.game.createHtml(sharedSpaceNotAllowedIconDiv, "aoc-board");
  }

  /**
   * Adds a red X icon to the board to indicate that the player cannot take a taxi
   *
   * @returns
   */
  addTaxiNotAllowed() {
    if (dojo.byId("aoc-taxi-not-allowed-icon")) return;

    const taxiNotAllowedIconDiv =
      "<div id='aoc-taxi-not-allowed-icon' class='aoc-not-allowed aoc-taxi-not-allowed'></div>";
    this.game.createHtml(taxiNotAllowedIconDiv, "aoc-board");
  }

  /**
   * Adds a red X icon to the board to indicate that the player cannot walk
   *
   * @returns
   */
  addWalkNotAllowed() {
    if (dojo.byId("aoc-walk-not-allowed-icon")) return;

    const walkNotAllowedIconDiv =
      "<div id='aoc-walk-not-allowed-icon' class='aoc-not-allowed aoc-walk-not-allowed'></div>";
    this.game.createHtml(walkNotAllowedIconDiv, "aoc-board");
  }

  /**
   * If player cancels the sales order action, reset the action panel
   */
  cancelSalesOrderAction(): void {
    this.resetActionPanel();
  }

  /**
   * If player cancels the ticket action, reset the state
   */
  cancelTicket(): void {
    dojo.destroy("aoc-cancel-use-ticket");
    this.resetUX();
    this.onEnteringState(this.stateArgs);
    dojo.removeClass("aoc-end-sales", "aoc-button-disabled");
    dojo.removeClass("aoc-use-ticket", "aoc-button-disabled");
  }

  /**
   * Handle when a player clicks a sales order to interact with it
   *
   * @param salesOrderTileId - the id of the sales order tile div
   */
  clickSalesOrder(salesOrderTileId: string): void {
    // Reset the action panel in case the player has already selected a sales order
    this.resetActionPanel();

    // Get the sales order tile div and clone it to the selected sales order container
    const salesOrderTile = dojo.byId(salesOrderTileId);
    const tileId = salesOrderTileId.split("-")[2];
    var selectedSalesOrderTile = dojo.clone(salesOrderTile);
    dojo.attr(selectedSalesOrderTile, "id", "aoc-selected-sales-order");
    dojo.attr(selectedSalesOrderTile, "sales-order-id", tileId);
    dojo.removeClass(selectedSalesOrderTile, "aoc-clickable");
    dojo.place(selectedSalesOrderTile, "aoc-selected-sales-order-container");

    // Highlight the selected sales order tileon the map
    dojo.addClass(salesOrderTile, "aoc-selected");

    // Enable the flip button if the player has remaining flip actions and the tile is face down
    if (this.remainingFlipActions > 0 && this.isTileFacedown(salesOrderTile)) {
      dojo.removeClass("aoc-flip-button", "aoc-button-disabled");
    }
    // Enable the collect button if the player has remaining collect actions
    if (this.remainingCollectActions > 0) {
      dojo.removeClass("aoc-collect-button", "aoc-button-disabled");
    }
  }

  /**
   * Collect the selected sales order
   */
  collectSalesOrder(): void {
    // Get the id of the selected sales order
    const salesOrderId = dojo
      .byId("aoc-selected-sales-order")
      .getAttribute("sales-order-id");

    dojo.setAttr("aoc-salesorder-" + salesOrderId, "collected", "true");

    // Reset the UX to prevent further interactions
    this.resetUX();

    // Decrement the collect counter
    this.collectsCounter.incValue(-1);

    // Send the collect sales order action to the server
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_COLLECT_SALES_ORDER, {
      salesOrderId: salesOrderId,
      playerIdToPay: this.playerIdToPay,
    });
  }

  /**
   * Create the counters that track remaining flip and collect actions
   */
  createActionCounters(): void {
    this.flipsCounter = new ebg.counter();
    this.flipsCounter.create("aoc-remaining-flips");
    this.flipsCounter.setValue(this.remainingFlipActions);

    this.collectsCounter = new ebg.counter();
    this.collectsCounter.create("aoc-remaining-collects");
    this.collectsCounter.setValue(this.remainingCollectActions);
  }

  /**
   * Create the div that tracks remaining sales actions
   */
  createRemainingActionsDiv(): void {
    // Get the div that contains the remaining actions
    const actionsDiv = document.getElementById("aoc-remaining-actions");

    // If the div already exists, return
    if (actionsDiv) return;

    // Create the div that will contain the remaining actions trackers
    const remainingActionsDiv =
      "<div id='aoc-remaining-actions' class='aoc-action-panel-row'></div>";
    this.game.createHtml(remainingActionsDiv, "page-title");

    // Create the div that will contain the remaining flip actions
    const remainingFlipsContainerDiv = `<div id='aoc-remaining-flips-container' class='aoc-player-panel-supply aoc-player-panel-other-supply'><span id='aoc-remaining-flips' class='aoc-player-panel-supply-count aoc-squada' style="padding-right: 5px !important"></span><span id='aoc-remaining-flips-icon' class='aoc-sales-action-icon aoc-sales-action-flip'></span></div>`;
    this.game.createHtml(remainingFlipsContainerDiv, "aoc-remaining-actions");

    // Create the div that will contain the remaining collect actions
    const remainingCollectsContainerDiv = `<div id='aoc-remaining-collects-container' class='aoc-player-panel-supply aoc-player-panel-other-supply'><span id='aoc-remaining-collects' class='aoc-player-panel-supply-count aoc-squada'></span><span id='aoc-remaining-collects-icon' class='aoc-sales-action-icon aoc-sales-action-collect'></span></div>`;
    this.game.createHtml(
      remainingCollectsContainerDiv,
      "aoc-remaining-actions"
    );
  }

  /**
   * Create the div that lets a player interact with a selected sales order
   *
   * @returns
   */
  createFlipOrCollectCounterDiv(): void {
    // Get the div that contains the flip or collect counter
    const interactionDiv = document.getElementById(
      "aoc-flip-or-collect-counter"
    );

    // If the div already exists, recreate the connections and return
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

    // Create the div that will contain the flip or collect counter
    const flipOrCollectCounterDiv =
      "<div id='aoc-flip-or-collect-counter' class='aoc-action-panel-row'></div>";
    this.game.createHtml(flipOrCollectCounterDiv, "page-title");

    // Create the div that will contain the selected sales order
    const selectedSalesOrderContainerDiv =
      "<div id='aoc-selected-sales-order-container' class='aoc-sales-order-selection-container'></div>";
    this.game.createHtml(
      selectedSalesOrderContainerDiv,
      "aoc-flip-or-collect-counter"
    );

    // Create the flip button w/ connection
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

    // Create the collect button w/ connection
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

    // Create the cancel button w/ connection
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
   * Determine which player the active player needs to pay if they take actions on a shared space.
   * If there are multiple players on the space, the player who arrived last will be the one to pay.
   *
   * @param space - the space the player is on
   * @param activePlayerId - the id of the active player
   */
  determinePlayerToPayForSpace(space: number, activePlayerId: number): void {
    // The player is on the start space, so they don't need to pay anyone
    if (space === 0) return;

    // Get the sales agents on the space
    const agentsOnSpace = this.getSalesAgentsOnSpace(space);

    // Initialize variables to track the player who arrived last
    var opponentArrivedLast = -1;
    var opponentArrivedId = -1;

    // If there are multiple players on the space, determine which player arrived last
    if (agentsOnSpace.length > 1) {
      // Loop through the agents on the space
      for (const agent of agentsOnSpace) {
        // Skip the active player
        if (parseInt(agent.id.split("-")[2]) === activePlayerId) {
          continue;
        }

        // Get the arrived attribute of the agent
        const arrived = parseInt(agent.getAttribute("arrived"));

        // If the agent arrived later than the last latest agent, update the opponentArrivedLast and opponentArrivedId
        if (arrived > opponentArrivedLast) {
          opponentArrivedLast = arrived;
          opponentArrivedId = parseInt(agent.id.split("-")[2]);
        }
      }
      // Set the playerIdToPay to the opponent who arrived last
      this.playerIdToPay = opponentArrivedId;

      // Highlight the section of the board to let player know doing an action here will require payment
      const highlightPayPlayer = "<div id='aoc-highlight-pay-player'></div>";
      this.game.createHtml(highlightPayPlayer, "aoc-board");
    } else {
      // The only agent on the space is the active player, so they don't need to pay anyone
      this.playerIdToPay = 0;
    }
  }

  /**
   * End the sales phase
   */
  endSales(): void {
    // Reset the UX and send the end sales action to the server
    this.resetUX(true);
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_END_SALES, {});
  }

  /**
   * Flip the selected sales order
   */
  flipSalesOrder(): void {
    // Get the id of the selected sales order
    const salesOrderId = dojo
      .byId("aoc-selected-sales-order")
      .getAttribute("sales-order-id");

    // Reset the UX to prevent further interactions
    this.resetUX();

    // Decrement the flip counter
    this.flipsCounter.incValue(-1);

    // Send the flip sales order action to the server
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_FLIP_SALES_ORDER, {
      salesOrderId: salesOrderId,
      playerIdToPay: this.playerIdToPay,
    });
  }

  /**
   * Get a list of sales order tiles connected to the agent space
   *
   * @param agentSpace - the space the sales agent is on
   * @returns
   */
  getConnectedSalesOrderTiles(agentSpace: number): any[] {
    // Get the spaces connected to the agent space
    const connectedSpaces = this.salesOrderConnections[agentSpace];

    // If there are no connected spaces, return an empty array
    if (!connectedSpaces) return [];

    // Initialize an array to hold the sales order tiles
    var salesOrderTiles = [];

    // Loop through the connected spaces and get the sales order tiles on those spaces
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

  /**
   * Gets a list of sales agent divs on a space
   *
   * @param space
   * @returns
   */
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

  /**
   * Highlight the sales order spaces that the player can interact with from the current space
   *
   * @param agentSpace - the space the sales agent is on
   * @param remainingCollectActions - the number of collect actions the player has remaining
   */
  highlightConnectedSalesOrderSpaces(
    agentSpace: number,
    remainingCollectActions: number
  ): void {
    // Get the sales order tiles connected to the agent space
    const salesOrderTiles = this.getConnectedSalesOrderTiles(agentSpace);

    // Loop through the sales order tiles and highlight the ones the player can interact with
    for (const salesOrderTile of salesOrderTiles) {
      // If the tile is face up and the player has no remaining collect actions, skip it as it can't be flipped
      if (
        !this.isTileFacedown(salesOrderTile) &&
        remainingCollectActions === 0
      ) {
        continue;
      }

      // If the tile has already been collected, skip it
      if (dojo.attr(salesOrderTile, "collected") === "true") {
        continue;
      }

      // Highlight the sales order tile and create a click listener for it
      dojo.addClass(salesOrderTile.id, "aoc-clickable");
      this.connections[salesOrderTile.id] = dojo.connect(
        salesOrderTile,
        "onclick",
        dojo.hitch(this, this.clickSalesOrder, salesOrderTile.id)
      );
    }
  }

  /**
   * Check if a sales order tile is face down
   *
   * @param salesOrderTile - the sales order tile div
   * @returns
   */
  isTileFacedown(salesOrderTile: any): boolean {
    for (const divClass of salesOrderTile.classList) {
      if (divClass.includes("facedown")) {
        return true;
      }
    }
    return false;
  }

  /**
   * Move the sales agent to a selected space
   *
   * @param space
   */
  moveSalesAgentToSpace(space: number): void {
    this.resetUX();
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_MOVE_SALES_AGENT, {
      space: space,
    });
  }

  /**
   * Move the sales agent to a selected space using a transport ticket
   *
   * @param space
   */
  moveSalesAgentToSpaceWithTicket(space: number): void {
    this.resetUX();
    this.game.ajaxcallwrapper(
      globalThis.PLAYER_ACTION_MOVE_SALES_AGENT_WITH_TICKET,
      {
        space: space,
      }
    );
  }

  /**
   * Reset the action panel
   */
  resetActionPanel(): void {
    // Remove the selected sales order div
    dojo.destroy("aoc-selected-sales-order");
    // Remove the selected sales order highlight on the map
    dojo.query(".aoc-selected").removeClass("aoc-selected");
    // Reset the flip and collect buttons
    dojo.addClass("aoc-flip-button", "aoc-button-disabled");
    dojo.addClass("aoc-collect-button", "aoc-button-disabled");
  }

  /**
   * Reset the UX to the default state
   *
   * @param removeActionBanner - whether to remove the action banner divs
   */
  resetUX(removeActionBanner: boolean = false): void {
    // Reset the action panel
    this.resetActionPanel();

    // Remove the highlight divs and click listeners
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");
    dojo.query(".aoc-button").forEach((button) => {
      dojo.addClass(button, "aoc-button-disabled");
    });

    // Destroy highlight that indicates player needs to pay
    dojo.destroy("aoc-highlight-pay-player");

    // Remove the action banner if needed
    // This is optional so the banner will stay on screen when the player re-enters the state after their action
    if (removeActionBanner) {
      dojo.destroy("aoc-remaining-actions");
      dojo.destroy("aoc-flip-or-collect-counter");
      dojo.destroy("aoc-walk-not-allowed-icon");
      dojo.destroy("aoc-taxi-not-allowed-icon");
      dojo.destroy("aoc-shared-space-not-allowed-icon");
    }

    // Delete all connections to prevent asding duplicate listeners
    for (const connection in this.connections) {
      dojo.disconnect(this.connections[connection]);
    }
    this.connections = {};
  }

  /**
   * Player selects to use the super-transport ticket
   */
  useTicket(): void {
    // Reset the UX to prevent further interactions
    this.resetUX();
    dojo.addClass("aoc-use-ticket", "aoc-button-disabled");

    // Get ALL the agent spaces
    const agentSpaces = globalThis.SALES_AGENT_CONNECTIONS;

    // Highlight w/connection every space on the board
    for (const space of Object.keys(agentSpaces)) {
      const divId = `aoc-map-agent-space-${space}`;
      dojo.addClass(divId, "aoc-clickable");
      this.connections[divId] = dojo.connect(
        dojo.byId(divId),
        "onclick",
        dojo.hitch(this, this.moveSalesAgentToSpaceWithTicket, space)
      );
    }

    // Add a cancel button to allow player to cancel the ticket action
    gameui.addActionButton(
      "aoc-cancel-use-ticket",
      _("Cancel using ticket"),
      () => {
        this.cancelTicket();
      }
    );
    dojo.addClass("aoc-cancel-use-ticket", "aoc-button");
  }
}
