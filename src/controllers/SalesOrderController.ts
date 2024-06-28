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
 * Handles sales order logic on front-end
 *
 */

class SalesOrderController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  /**
   * Set up sales orders
   *
   * @param salesOrders - current sales order data used to initialize UI
   */
  setupSalesOrders(salesOrders: any): void {
    for (var key in salesOrders) {
      this.createSalesOrder(salesOrders[key]);
    }
  }

  collectSalesOrder(salesOrder: any): void {
    this.flipSalesOrder(salesOrder);
    const salesOrderDiv = "aoc-salesorder-" + salesOrder.id;
    const targetDiv = "aoc-sales-order-container-" + salesOrder.locationArg;
    const animation = this.ui.slideToObject(salesOrderDiv, targetDiv);
    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(salesOrderDiv, "style");
      dojo.place(salesOrderDiv, targetDiv);
    });
    animation.play();
  }

  /**
   * Creates a sales order
   *
   * @param salesOrder - sales order data
   */
  createSalesOrder(salesOrder: any): void {
    var salesOrderDiv =
      '<div id="aoc-salesorder-' +
      salesOrder.id +
      '" class="aoc-salesorder ' +
      salesOrder.cssClass +
      '"></div>';

    if (salesOrder.location == globalThis.LOCATION_MAP) {
      this.ui.createHtml(
        salesOrderDiv,
        "aoc-map-order-space-" + salesOrder.locationArg
      );
    }

    if (salesOrder.location == globalThis.LOCATION_PLAYER_AREA) {
      this.ui.createHtml(
        salesOrderDiv,
        "aoc-sales-order-container-" + salesOrder.locationArg
      );
    }
  }

  discardSalesOrder(salesOrder: any): void {
    dojo.destroy("aoc-salesorder-" + salesOrder.id);
  }

  /**
   * Flips a sales order
   *
   * @param salesOrder - sales order data
   */
  flipSalesOrder(salesOrder: any): void {
    var salesOrderDiv = dojo.byId("aoc-salesorder-" + salesOrder.id);
    if (
      salesOrderDiv.classList.contains(
        "aoc-salesorder-" + salesOrder.genre + "-facedown"
      )
    ) {
      dojo.removeClass(
        salesOrderDiv,
        "aoc-salesorder-" + salesOrder.genre + "-facedown"
      );
      dojo.addClass(salesOrderDiv, salesOrder.cssClass);
    }
  }

  /**
   * Flips sales orders
   *
   * @param salesOrders - sales order data
   */
  flipSalesOrders(salesOrders: any): void {
    for (var key in salesOrders) {
      this.flipSalesOrder(salesOrders[key]);
    }
  }
}
