/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * TicketController.ts
 *
 * Handles ticket logic on front-end
 *
 */

class TicketController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  /**
   * Set up tickets
   * @param {object} tickets - current ticket data used to initialize UI
   */
  setupTickets(ticketCount: any): void {
    for (var i = 1; i <= ticketCount; i++) {
      this.createTicket(i);
    }
  }

  /**
   * Creates a ticket
   * @param {object} ticketNum - ticket number
   */
  createTicket(ticketNum: any): void {
    var ticketDiv =
      '<div id="aoc-ticket-' + ticketNum + '" class="aoc-ticket"></div>';
    this.ui.createHtml(ticketDiv, "aoc-tickets-space");
  }

  gainTicket(player: any): void {
    const tickets = dojo.query(".aoc-ticket");
    if (tickets.length === 0) {
      return;
    }
    const ticket = tickets[0];
    this.ui.slideToObjectAndDestroy(
      ticket,
      "aoc-player-ticket-" + player.id,
      1000
    );
  }
}
