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
  salesAgentConnections: any;
  salesOrderConnections: any;
  constructor(game: any) {
    this.game = game;
    this.salesAgentConnections = {};
    this.salesOrderConnections = {};
  }

  onEnteringState(stateArgs: any): void {
    // Create the remaining actions div
    this.createRemainingActionsDiv(
      stateArgs.args.remainingFlipActions,
      stateArgs.args.remainingCollectActions
    );

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

  /**
   * Create the div that tracks remaining actions
   */
  createRemainingActionsDiv(remainingFlips: number, remainingCollects: number): void {
    const actionsDiv = document.getElementById("aoc-remaining-actions");

    // If the div already exists, return
    if (actionsDiv) return;

    const remainingActionsDiv =
      "<div id='aoc-remaining-actions' class='aoc-action-panel-row'></div>";
    this.game.createHtml(remainingActionsDiv, "page-title");

    const remainingFlipsDiv = `<div class='aoc-remaining-action'>${_(
      "Remaining flips"
    )}: ${remainingFlips}</div>`;
    this.game.createHtml(remainingFlipsDiv, "aoc-remaining-actions");

    const remainingCollectsDiv = `<div class='aoc-remaining-action'>${_(
      "Remaining collects"
    )}: ${remainingCollects}</div>`;
    this.game.createHtml(remainingCollectsDiv, "aoc-remaining-actions");
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
    }
  }

  useTicket(): void {
    console.log("Using ticket");
  }
}
