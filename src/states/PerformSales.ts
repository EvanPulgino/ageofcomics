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
  constructor(game: any) {
    this.game = game;
    this.connections = {};
    this.salesAgentConnections = {};
    this.salesOrderConnections = {};
    this.flipsCounter = {};
    this.collectsCounter = {};
  }

  onEnteringState(stateArgs: any): void {
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
      this.salesAgentConnections = globalThis.SALES_AGENT_CONNECTIONS;
      this.salesOrderConnections = globalThis.SALES_ORDER_CONNECTIONS;

      // Highlight the spaces the sales agent can move to
      // The player can only move if they haven't used their free walk action
      // or they have enough money to pay to take a cab
      if (!stateArgs.args.hasWalked || stateArgs.args.playerMoney >= 2)
        this.highlightAdjacentSalesAgentSpaces(
          stateArgs.args.salesAgentLocation
        );
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

  /**
   * End the sales phase
   */
  endSales(): void {
    console.log("Ending sales phase");
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

  moveSalesAgentToSpace(space: number): void {
    this.resetUX();
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_MOVE_SALES_AGENT, {
      space: space,
    });
  }

  resetUX(removeActionBanner: boolean = false): void {
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
