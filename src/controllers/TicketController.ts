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
 */

class TicketController extends GameBasics {

    setupTickets(ticketCount: any) : void {
        for (var i = 1; i <= ticketCount; i++) {
            this.createTicket(i);
        }
    }

    createTicket(ticketNum: any) : void {
        var ticketDiv =
          '<div id="aoc-ticket-'+ticketNum+'" class="aoc-ticket"></div>';
        this.createHtml(ticketDiv, "aoc-tickets-space");
    }
}