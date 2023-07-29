/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * GameBody.ts
 *
 * Main game logic
 *
 */

// @ts-ignore
class GameBody extends GameBasics {
  states: any;
  gameController: GameController;
  playerController: PlayerController;
  calendarController: CalendarController;
  editorController: EditorController;
  miniComicController: MiniComicController;
  ripoffController: RipoffController;
  salesOrderController: SalesOrderController;
  ticketController: TicketController;
  constructor() {
    super();
    this.gameController = new GameController();
    this.playerController = new PlayerController();
    this.calendarController = new CalendarController();
    this.editorController = new EditorController();
    this.miniComicController = new MiniComicController();
    this.ripoffController = new RipoffController();
    this.salesOrderController = new SalesOrderController();
    this.ticketController = new TicketController();

    dojo.connect(
      window,
      "onresize",
      this,
      dojo.hitch(this, "adaptViewportSize")
    );
  }

  adaptViewportSize() {
    var t = dojo.marginBox("aoc-overall");
    var r = t.w;
    var s = 2772;
    var height = dojo.marginBox("aoc-layout").h;
    var viewportWidth = dojo.window.getBox().w;
    var gameAreaWidth = viewportWidth < 980 ? viewportWidth : viewportWidth - 245;

    if (r >= s) {
      var i = (r - s) / 2;
      dojo.style("aoc-gboard", "transform", "");
      dojo.style("aoc-gboard", "left", i + "px");
      dojo.style("aoc-gboard", "height", height + "px");
      dojo.style("aoc-gboard", "width", gameAreaWidth + "px");
    } else {
      var o = r / s;
      i = (t.w - r) / 2;
      var width = viewportWidth <= 245 ? gameAreaWidth : gameAreaWidth / o;
      dojo.style("aoc-gboard", "transform", "scale(" + o + ")");
      dojo.style("aoc-gboard", "left", i + "px");
      dojo.style("aoc-gboard", "height", height * o + "px");
      dojo.style("aoc-gboard", "width", width + "px");
    }
  }

  /**
   * UI setup entry point
   *
   * @param {object} gamedata - current game data used to initialize UI
   */
  setup(gamedata: any) {
    super.setup(gamedata);
    this.gameController.setup(gamedata);
    this.playerController.setupPlayers(gamedata.playerInfo);
    this.calendarController.setupCalendar(gamedata.calendarTiles);
    this.editorController.setupEditors(gamedata.editors);
    this.miniComicController.setupMiniComics(gamedata.miniComics);
    this.ripoffController.setupRipoffCards(gamedata.ripoffCards);
    this.salesOrderController.setupSalesOrders(gamedata.salesOrders);
    this.ticketController.setupTickets(gamedata.ticketSupply);
    this.setupNotifications();
    this.debug("Ending game setup");
  }

  /**
   * Handle button click events
   *
   * @param {object} event - event triggered
   */
  onButtonClick(event: any): void {
    this.debug("onButtonClick", event);
  }

  /**
   * Setups and subscribes to notifications
   */
  setupNotifications(): void {
    for (var m in this) {
      if (typeof this[m] == "function" && m.startsWith("notif_")) {
        dojo.subscribe(m.substring(6), this, m);
      }
    }
  }

  /**
   * Handle 'message' notification
   *
   * @param {object} notif - notification data
   */
  notif_message(notif: any): void {
    this.debug("notif", notif);
  }
}
