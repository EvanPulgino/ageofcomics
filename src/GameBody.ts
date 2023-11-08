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
  cardController: CardController;
  editorController: EditorController;
  masteryController: MasteryController;
  miniComicController: MiniComicController;
  ripoffController: RipoffController;
  salesOrderController: SalesOrderController;
  ticketController: TicketController;
  constructor() {
    super();
    this.gameController = new GameController(this);
    this.playerController = new PlayerController(this);
    this.calendarController = new CalendarController(this);
    this.cardController = new CardController(this);
    this.editorController = new EditorController(this);
    this.masteryController = new MasteryController(this);
    this.miniComicController = new MiniComicController(this);
    this.ripoffController = new RipoffController(this);
    this.salesOrderController = new SalesOrderController(this);
    this.ticketController = new TicketController(this);

    dojo.connect(
      window,
      "onresize",
      this,
      dojo.hitch(this, "adaptViewportSize")
    );
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
    this.cardController.setupPlayerHands(gamedata.playerHands);
    this.cardController.setupDeck(gamedata.artistDeck);
    this.cardController.setupDeck(gamedata.writerDeck);
    this.cardController.setupDeck(gamedata.comicDeck);
    this.cardController.setupSupply(gamedata.artistSupply);
    this.cardController.setupSupply(gamedata.writerSupply);
    this.cardController.setupSupply(gamedata.comicSupply);
    this.editorController.setupEditors(gamedata.editors);
    this.masteryController.setupMasteryTokens(gamedata.mastery);
    this.miniComicController.setupMiniComics(gamedata.miniComics);
    this.ripoffController.setupRipoffCards(gamedata.ripoffCards);
    this.salesOrderController.setupSalesOrders(gamedata.salesOrders);
    this.ticketController.setupTickets(gamedata.ticketSupply);
    this.setupNotifications();
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
    this.notifqueue.setSynchronous("discardCard", 500);
    this.notifqueue.setSynchronous("discardCardFromDeck", 500);
    this.notifqueue.setSynchronous("gainIdeaFromBoard", 500);
    this.notifqueue.setSynchronous("gainIdeaFromSupply", 500);
    this.notifqueue.setSynchronous("gainStartingIdea", 500);
    
    this.notifqueue.setIgnoreNotificationCheck(
      "developComic",
      function (notif: any) {
        return notif.args.player_id == gameui.player_id;
      }
    );
    this.notifqueue.setIgnoreNotificationCheck(
      "gainStartingComic",
      function (notif: any) {
        return notif.args.player_id == gameui.player_id;
      }
    );
    this.notifqueue.setIgnoreNotificationCheck(
      "hireCreative",
      function (notif: any) {
        return notif.args.player_id == gameui.player_id;
      }
    );
  }

  /**
   * Handle 'message' notification
   *
   * @param {object} notif - notification data
   */
  notif_message(notif: any): void {}

  notif_adjustMoney(notif: any): void {
    this.playerController.adjustMoney(notif.args.player, notif.args.amount);
  }

  notif_completeSetup(notif: any): void {
    this.cardController.setupDeck(notif.args.artistCards.deck);
    this.cardController.setupDeck(notif.args.writerCards.deck);
    this.cardController.setupDeck(notif.args.comicCards.deck);

    this.cardController.setupSupply(notif.args.artistCards.supply);
    this.cardController.setupSupply(notif.args.writerCards.supply);
    this.cardController.setupSupply(notif.args.comicCards.supply);
  }

  notif_developComic(notif: any): void {
    this.cardController.slideCardToPlayerHand(notif.args.comic);
  }

  notif_developComicPrivate(notif: any): void {
    this.cardController.slideCardToPlayerHand(notif.args.comic);
  }

  notif_discardCard(notif: any): void {
    this.cardController.discardCard(notif.args.card, notif.args.player.id);
  }

  notif_discardCardFromDeck(notif: any): void {
    this.cardController.discardCardFromDeck(notif.args.card);
  }

  notif_flipCalendarTiles(notif: any): void {
    this.calendarController.flipCalendarTiles(notif.args.flippedTiles);
  }

  notif_flipSalesOrders(notif: any): void {
    this.salesOrderController.flipSalesOrders(notif.args.flippedSalesOrders);
  }

  notif_gainIdeaFromBoard(notif: any): void {
    this.playerController.gainIdeaFromBoard(
      notif.args.player.id,
      notif.args.genre
    );
  }

  notif_gainIdeaFromHiringCreative(notif: any): void {
    this.playerController.gainIdeaFromHiringCreative(
      notif.args.player.id,
      notif.args.genre,
      notif.args.card.id
    );
  }

  notif_gainIdeaFromSupply(notif: any): void {
    this.playerController.gainIdeaFromSupply(
      notif.args.player.id,
      notif.args.genre
    );
  }

  notif_gainStartingComic(notif: any): void {
    this.cardController.gainStartingComic(notif.args.comic_card);
  }

  notif_gainStartingComicPrivate(notif: any): void {
    this.cardController.gainStartingComic(notif.args.comic_card);
  }

  notif_gainStartingIdea(notif: any): void {
    this.playerController.gainStartingIdea(
      notif.args.player_id,
      notif.args.genre
    );
  }

  notif_hireCreative(notif: any): void {
    this.cardController.slideCardToPlayerHand(notif.args.card);
  }

  notif_hireCreativePrivate(notif: any): void {
    this.cardController.slideCardToPlayerHand(notif.args.card);
  }

  notif_placeEditor(notif: any): void {
    this.editorController.moveEditorToActionSpace(
      notif.args.editor,
      notif.args.space
    );
  }

  notif_reshuffleDiscardPile(notif: any): void {
    this.cardController.setupDeck(notif.args.deck);
  }

  /**
   * Handle 'setupMoney' notification
   *
   * @param {object} notif - notification data
   */
  notif_setupMoney(notif: any): void {
    this.playerController.adjustMoney(notif.args.player, notif.args.money);
  }

  notif_takeRoyalties(notif: any): void {
    this.playerController.adjustMoney(notif.args.player, notif.args.amount);
  }
}
