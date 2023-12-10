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
  gameController: GameController;
  playerController: PlayerController;
  calendarController: CalendarController;
  cardController: CardController;
  editorController: EditorController;
  ideaController: IdeaController;
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
    this.ideaController = new IdeaController(this);
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
    this.cardController.setupCards(gamedata.cards);
    this.editorController.setupEditors(gamedata.editors);
    this.ideaController.setupIdeas(gamedata.ideasSpaceContents);
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
    this.notifqueue.setSynchronous("assignComic", 500);
    this.notifqueue.setSynchronous("assignCreative", 500);
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

  notif_addMiniComicToChart(notif: any): void {
    this.miniComicController.moveMiniComicToChart(notif.args.miniComic);
  }

  notif_adjustIdeas(notif: any): void {
    this.playerController.adjustIdeas(
      notif.args.player,
      notif.args.genre,
      notif.args.numOfIdeas
    );
  }

  /**
   * Handle 'adjustMoney' notification
   *
   * Notif args:
   * - player: player object
   * - amount: amount to adjust by
   *
   * @param notif
   */
  notif_adjustMoney(notif: any): void {
    this.playerController.adjustMoney(notif.args.player, notif.args.amount);
  }

  notif_assignComic(notif: any): void {
    this.cardController.slideCardToPlayerMat(
      notif.args.player,
      notif.args.card,
      notif.args.slot
    );

    if (notif.args.spentIdeas > 0) {
      this.playerController.adjustIdeas(
        notif.args.player,
        notif.args.card.genre,
        -notif.args.spentIdeas
      );
    }
  }

  notif_assignCreative(notif: any): void {
    this.cardController.slideCardToPlayerMat(
      notif.args.player,
      notif.args.card,
      notif.args.slot
    );
    this.playerController.adjustMoney(notif.args.player, -notif.args.cost);
  }

  /**
   * Handle'completeSetup' notification
   *
   * Notif args:
   * - artistCards: {deck: array, supply: array}
   * - writerCards: {deck: array, supply: array}
   *
   * @param notif
   */
  notif_completeSetup(notif: any): void {
    this.cardController.setupCards(notif.args.artistCards.deck);
    this.cardController.setupCards(notif.args.writerCards.deck);
    this.cardController.setupCards(notif.args.comicCards.deck);

    this.cardController.setupCards(notif.args.artistCards.supply);
    this.cardController.setupCards(notif.args.writerCards.supply);
    this.cardController.setupCards(notif.args.comicCards.supply);
  }

  /**
   * Handle 'developComic' notification
   *
   * Notif args:
   * - comic: comic card
   *
   * @param notif
   */
  notif_developComic(notif: any): void {
    this.cardController.slideCardToPlayerHand(notif.args.comic);
    this.playerController.adjustHand(notif.args.player, 1);
  }

  /**
   * Handle 'developComicPrivate' notification
   *
   * Notif args:
   * - comic: comic card
   *
   * @param notif
   */
  notif_developComicPrivate(notif: any): void {
    this.cardController.slideCardToPlayerHand(notif.args.comic);
    this.playerController.adjustHand(notif.args.player, 1);
  }

  /**
   * Handle 'discardCard' notification
   *
   * Notif args:
   * - card: card to discard
   * - player: player object
   *
   * @param notif
   */
  notif_discardCard(notif: any): void {
    this.cardController.discardCard(notif.args.card, notif.args.player.id);
    this.playerController.adjustHand(notif.args.player, -1);
  }

  /**
   * Handle 'discardCardFromDeck' notification
   *
   * Notif args:
   * - card: card to discard
   *
   * @param notif
   */
  notif_discardCardFromDeck(notif: any): void {
    this.cardController.discardCardFromDeck(notif.args.card);
  }

  /**
   * Handle 'flipCalendarTiles' notification
   *
   * Notif args:
   * - flippedTiles: array of flipped tiles
   *
   * @param notif
   */
  notif_flipCalendarTiles(notif: any): void {
    this.calendarController.flipCalendarTiles(notif.args.flippedTiles);
  }

  /**
   * Handle 'flipSalesOrders' notification
   *
   * Notif args:
   * - flippedSalesOrders: array of flipped sales orders
   *
   * @param notif
   */
  notif_flipSalesOrders(notif: any): void {
    this.salesOrderController.flipSalesOrders(notif.args.flippedSalesOrders);
  }

  /**
   * Handle 'gainIdeaFromBoard' notification
   *
   * Notif args:
   * - player: player object
   * - genre: genre of idea
   *
   * @param notif
   */
  notif_gainIdeaFromBoard(notif: any): void {
    this.ideaController.gainIdeaFromBoard(
      notif.args.player.id,
      notif.args.genre
    );
    this.playerController.adjustIdeas(notif.args.player, notif.args.genre, 1);
  }

  /**
   * Handle 'gainIdeaFromHiringCreative' notification
   *
   * Notif args:
   * - player: player object
   * - genre: genre of idea
   * - card: card object
   *
   * @param notif
   */
  notif_gainIdeaFromHiringCreative(notif: any): void {
    this.ideaController.gainIdeaFromHiringCreative(
      notif.args.player.id,
      notif.args.genre,
      notif.args.card.id
    );
    this.playerController.adjustIdeas(notif.args.player, notif.args.genre, 1);
  }

  /**
   * Handle 'gainIdeaFromSupply' notification
   *
   * Notif args:
   * - player: player object
   * - genre: genre of idea
   *
   * @param notif
   */
  notif_gainIdeaFromSupply(notif: any): void {
    this.ideaController.gainIdeaFromSupply(
      notif.args.player.id,
      notif.args.genre
    );
    this.playerController.adjustIdeas(notif.args.player, notif.args.genre, 1);
  }

  /**
   * Handle 'gainStartingComic' notification
   *
   * Notif args:
   * - player: player object
   * - comic_card: comic card
   *
   * @param notif
   */
  notif_gainStartingComic(notif: any): void {
    this.cardController.gainStartingComic(notif.args.comic_card);
    this.playerController.adjustHand(notif.args.player, 1);
  }

  /**
   * Handle 'gainStartingComicPrivate' notification
   *
   * Notif args:
   * - comic_card: comic card
   *
   * @param notif
   */
  notif_gainStartingComicPrivate(notif: any): void {
    this.cardController.gainStartingComic(notif.args.comic_card);
    this.playerController.adjustHand(notif.args.player, 1);
  }

  /**
   * Handle 'gainStartingIdea' notification
   *
   * Notif args:
   * - player: player object
   * - genre: genre of idea
   *
   * @param notif
   */
  notif_gainStartingIdea(notif: any): void {
    this.ideaController.gainStartingIdea(
      notif.args.player.id,
      notif.args.genre
    );
    this.playerController.adjustIdeas(notif.args.player, notif.args.genre, 1);
  }

  /**
   * Handle 'hireCreative' notification
   *
   * Notif args:
   * - card: card to hire
   * - player: player object
   *
   * @param notif
   */
  notif_hireCreative(notif: any): void {
    this.cardController.slideCardToPlayerHand(notif.args.card);
    this.playerController.adjustHand(notif.args.player, 1);
  }

  /**
   * Handle 'hireCreativePrivate' notification
   *
   * Notif args:
   * - card: card to hire
   *
   * @param notif
   */
  notif_hireCreativePrivate(notif: any): void {
    this.cardController.slideCardToPlayerHand(notif.args.card);
    this.playerController.adjustHand(notif.args.player, 1);
  }

  /**
   * Handle 'placeEditor' notification
   *
   * Notif args:
   * - editor: editor object
   * - space: space to place editor
   *
   * @param notif
   */
  notif_placeEditor(notif: any): void {
    this.editorController.moveEditorToActionSpace(
      notif.args.editor,
      notif.args.space
    );
  }

  /**
   * Handle'reshuffleDiscardPile' notification
   *
   * Notif args:
   * - deck: array of cards in deck
   *
   * @param notif
   */
  notif_reshuffleDiscardPile(notif: any): void {
    this.cardController.setupCards(notif.args.deck);
  }

  /**
   * Handle 'setupMoney' notification
   *
   * Notif args:
   * - player: player object
   * - money: amount of money to set
   *
   * @param {object} notif - notification data
   */
  notif_setupMoney(notif: any): void {
    this.playerController.adjustMoney(notif.args.player, notif.args.money);
  }

  /**
   * Handle 'takeRoyalties' notification
   *
   * Notif args:
   * - player: player object
   * - amount: amount to adjust by
   *
   * @param notif
   */
  notif_takeRoyalties(notif: any): void {
    this.playerController.adjustMoney(notif.args.player, notif.args.amount);
  }
}
