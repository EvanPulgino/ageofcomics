/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * SalesOrderController.ts
 *
 */

class SalesOrderController extends GameBasics {

    setupSalesOrders(salesOrders: any) : void {
        for (var key in salesOrders) {
            this.createSalesOrder(salesOrders[key]);
        }
    }

    createSalesOrder(salesOrder: any) : void {
        this.debug("creating sales order", salesOrder);
        var salesOrderDiv = '<div id="aoc-salesorder-' + salesOrder.id + '" class="aoc-salesorder ' + salesOrder.cssClass + '"></div>';

        if (salesOrder.location == globalThis.LOCATION_MAP) {
            this.createHtml(salesOrderDiv, "aoc-map-order-space-" + salesOrder.locationArg);
        }
    }
}