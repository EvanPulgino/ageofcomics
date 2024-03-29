/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * GameBasics.ts
 *
 * Class that extends default bga core game class with more functionality
 *
 */
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
// @ts-ignore
// @ts-nocheck
GameGui = /** @class */ (function () {
    function GameGui() { }
    return GameGui;
})();
var GameBasics = /** @class */ (function (_super) {
    __extends(GameBasics, _super);
    function GameBasics() {
        var _this = _super.call(this) || this;
        _this.isDebug = window.location.host == "studio.boardgamearena.com";
        _this.debug = _this.isDebug ? console.info.bind(window.console) : function () { };
        _this.debug("GameBasics constructor", _this);
        _this.curstate = null;
        _this.pendingUpdate = false;
        _this.currentPlayerWasActive = false;
        _this.gameState = new GameState(_this);
        return _this;
    }
    /**
     * Change the viewport size based on current window size
     * Called when window is resized and a few other places
     *
     * @returns {void}
     */
    GameBasics.prototype.adaptViewportSize = function () {
        var t = dojo.marginBox("aoc-overall");
        var r = t.w;
        var s = 1500;
        var height = dojo.marginBox("aoc-layout").h;
        var viewportWidth = dojo.window.getBox().w;
        var gameAreaWidth = viewportWidth < 980 ? viewportWidth : viewportWidth - 245;
        if (r >= s) {
            var i = (r - s) / 2;
            dojo.style("aoc-gboard", "transform", "");
            dojo.style("aoc-gboard", "left", i + "px");
            dojo.style("aoc-gboard", "height", height + "px");
            dojo.style("aoc-gboard", "width", gameAreaWidth + "px");
        }
        else {
            var o = r / s;
            i = (t.w - r) / 2;
            var width = viewportWidth <= 245 ? gameAreaWidth : gameAreaWidth / o;
            dojo.style("aoc-gboard", "transform", "scale(" + o + ")");
            dojo.style("aoc-gboard", "left", i + "px");
            dojo.style("aoc-gboard", "height", height * o + "px");
            dojo.style("aoc-gboard", "width", width + "px");
        }
    };
    /**
     * UI setup entry point
     *
     * @param {object} gamedata - game data
     * @returns {void}
     */
    GameBasics.prototype.setup = function (gamedata) {
        this.debug("Game data", gamedata);
        this.defineGlobalConstants(gamedata.constants);
    };
    /**
     * Gives javascript access to PHP defined constants
     *
     * @param {object} constants - constants defined in PHP
     * @returns {void}
     */
    GameBasics.prototype.defineGlobalConstants = function (constants) {
        for (var constant in constants) {
            if (!globalThis[constant]) {
                globalThis[constant] = constants[constant];
            }
        }
    };
    /**
     * Called when game enters a new state
     *
     * @param {string} stateName - name of the state
     * @param {object} args - args passed to the state
     * @returns {void}
     */
    GameBasics.prototype.onEnteringState = function (stateName, args) {
        this.debug("onEnteringState: " + stateName, args, this.debugStateInfo());
        this.adaptViewportSize();
        this.curstate = stateName;
        args["isCurrentPlayerActive"] = gameui.isCurrentPlayerActive();
        this.gameState[stateName].onEnteringState(args);
        if (this.pendingUpdate) {
            this.onUpdateActionButtons(stateName, args);
            this.pendingUpdate = false;
        }
    };
    /**
     * Called when game leaves a state
     *
     * @param {string} stateName - name of the state
     * @returns {void}
     */
    GameBasics.prototype.onLeavingState = function (stateName) {
        this.debug("onLeavingState: " + stateName, this.debugStateInfo());
        this.currentPlayerWasActive = false;
        this.gameState[stateName].onLeavingState();
        this.adaptViewportSize();
    };
    /**
     * Builds action buttons on state change
     *
     * @param {string} stateName - name of the state
     * @param {object} args - args passed to the state
     * @returns {void}
     */
    GameBasics.prototype.onUpdateActionButtons = function (stateName, args) {
        if (this.curstate != stateName) {
            // delay firing this until onEnteringState is called so they always called in same order
            this.pendingUpdate = true;
            return;
        }
        this.pendingUpdate = false;
        if (gameui.isCurrentPlayerActive() &&
            this.currentPlayerWasActive == false) {
            this.debug("onUpdateActionButtons: " + stateName, args, this.debugStateInfo());
            this.currentPlayerWasActive = true;
            // Call appropriate method
            this.gameState[stateName].onUpdateActionButtons(args);
        }
        else {
            this.currentPlayerWasActive = false;
        }
    };
    /**
     * Get info about current state
     *
     * @returns {object} state info
     */
    GameBasics.prototype.debugStateInfo = function () {
        var iscurac = gameui.isCurrentPlayerActive();
        var replayMode = false;
        if (typeof g_replayFrom != "undefined") {
            replayMode = true;
        }
        var instantaneousMode = gameui.instantaneousMode ? true : false;
        var res = {
            isCurrentPlayerActive: iscurac,
            instantaneousMode: instantaneousMode,
            replayMode: replayMode,
        };
        return res;
    };
    /**
     * A wrapper to make AJAX calls to the game server
     *
     * @param {string} action - name of the action
     * @param {object=} args - args passed to the action
     * @param {requestCallback=} handler - callback function
     * @returns {void}
     */
    GameBasics.prototype.ajaxcallwrapper = function (action, args, handler) {
        if (!args) {
            args = {};
        }
        args.lock = true;
        if (gameui.checkAction(action)) {
            gameui.ajaxcall("/" +
                gameui.game_name +
                "/" +
                gameui.game_name +
                "/" +
                action +
                ".html", args, //
            gameui, function (result) { }, handler);
        }
    };
    /**
     * Creates and inserts HTML into the DOM
     *
     * @param {string} divstr - div to create
     * @param {string=} location - parent node to insert div into
     * @returns {any} div element
     */
    GameBasics.prototype.createHtml = function (divstr, location) {
        var tempHolder = document.createElement("div");
        tempHolder.innerHTML = divstr;
        var div = tempHolder.firstElementChild;
        var parentNode = document.getElementById(location);
        if (parentNode)
            parentNode.appendChild(div);
        return div;
    };
    /**
     * Creates a div and inserts it into the DOM
     *
     * @param {string=} id - id of div
     * @param {string=} classes - classes to add to div
     * @param {string=} location - parent node to insert div into
     * @returns {any}
     */
    GameBasics.prototype.createDiv = function (id, classes, location) {
        var _a;
        var div = document.createElement("div");
        if (id)
            div.id = id;
        if (classes)
            (_a = div.classList).add.apply(_a, classes.split(" "));
        var parentNode = document.getElementById(location);
        if (parentNode)
            parentNode.appendChild(div);
        return div;
    };
    /**
     * Calls a function if it exists
     *
     * @param {string} methodName - name of the function
     * @param {object} args - args passed to the function
     * @returns
     */
    GameBasics.prototype.callfn = function (methodName, args) {
        if (this[methodName] !== undefined) {
            this.debug("Calling " + methodName, args);
            return this[methodName](args);
        }
        return undefined;
    };
    /** @Override onScriptError from gameui */
    GameBasics.prototype.onScriptError = function (msg, url, linenumber) {
        if (gameui.page_is_unloading) {
            // Don't report errors during page unloading
            return;
        }
        // In anycase, report these errors in the console
        console.error(msg);
        // cannot call super - dojo still have to used here
        //super.onScriptError(msg, url, linenumber);
        return this.inherited(arguments);
    };
    /**
     * Get a map of all genre keys and values
     *
     * @returns {object} map of genre keys and values
     */
    GameBasics.prototype.getGenres = function () {
        return GENRES;
    };
    /**
     * Gets a genre key from a genre gam
     *
     * @param genre
     * @returns {number} genre id
     */
    GameBasics.prototype.getGenreId = function (genre) {
        for (var key in GENRES) {
            if (GENRES[key] == genre) {
                return parseInt(key);
            }
        }
    };
    /**
     * Gets a genre name from a genre id
     *
     * @param genreId
     * @returns {string} genre name
     */
    GameBasics.prototype.getGenreName = function (genreId) {
        return GENRES[genreId];
    };
    /**
     * Gets the name of a player's color from its hex value
     *
     * @param playerColor
     * @returns {string} player color name
     */
    GameBasics.prototype.getPlayerColorAsString = function (playerColor) {
        return PLAYER_COLORS[playerColor];
    };
    return GameBasics;
}(GameGui));
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
var GameBody = /** @class */ (function (_super) {
    __extends(GameBody, _super);
    function GameBody() {
        var _this = _super.call(this) || this;
        _this.gameController = new GameController(_this);
        _this.playerController = new PlayerController(_this);
        _this.calendarController = new CalendarController(_this);
        _this.cardController = new CardController(_this);
        _this.editorController = new EditorController(_this);
        _this.ideaController = new IdeaController(_this);
        _this.masteryController = new MasteryController(_this);
        _this.miniComicController = new MiniComicController(_this);
        _this.ripoffController = new RipoffController(_this);
        _this.salesOrderController = new SalesOrderController(_this);
        _this.ticketController = new TicketController(_this);
        dojo.connect(window, "onresize", _this, dojo.hitch(_this, "adaptViewportSize"));
        return _this;
    }
    /**
     * UI setup entry point
     *
     * @param {object} gamedata - current game data used to initialize UI
     */
    GameBody.prototype.setup = function (gamedata) {
        _super.prototype.setup.call(this, gamedata);
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
    };
    /**
     * Setups and subscribes to notifications
     */
    GameBody.prototype.setupNotifications = function () {
        for (var m in this) {
            if (typeof this[m] == "function" && m.startsWith("notif_")) {
                dojo.subscribe(m.substring(6), this, m);
            }
        }
        this.notifqueue.setSynchronous("adjustMiniComic", 500);
        this.notifqueue.setSynchronous("assignComic", 500);
        this.notifqueue.setSynchronous("assignCreative", 500);
        this.notifqueue.setSynchronous("discardCard", 500);
        this.notifqueue.setSynchronous("discardCardFromDeck", 500);
        this.notifqueue.setSynchronous("gainIdeaFromBoard", 500);
        this.notifqueue.setSynchronous("gainIdeaFromSupply", 500);
        this.notifqueue.setSynchronous("gainStartingIdea", 500);
        this.notifqueue.setSynchronous("masteryTokenClaimed", 500);
        this.notifqueue.setSynchronous("placeUpgradeCube", 500);
        this.notifqueue.setIgnoreNotificationCheck("developComic", function (notif) {
            return notif.args.player_id == gameui.player_id;
        });
        this.notifqueue.setIgnoreNotificationCheck("gainStartingComic", function (notif) {
            return notif.args.player_id == gameui.player_id;
        });
        this.notifqueue.setIgnoreNotificationCheck("hireCreative", function (notif) {
            return notif.args.player_id == gameui.player_id;
        });
    };
    /**
     * Handle 'message' notification
     *
     * @param {object} notif - notification data
     */
    GameBody.prototype.notif_message = function (notif) { };
    GameBody.prototype.notif_addMiniComicToChart = function (notif) {
        this.miniComicController.moveMiniComicToChart(notif.args.miniComic);
        this.playerController.adjustIncome(notif.args.player, notif.args.income);
    };
    GameBody.prototype.notif_adjustIdeas = function (notif) {
        this.playerController.adjustIdeas(notif.args.player, notif.args.genre, notif.args.numOfIdeas);
    };
    GameBody.prototype.notif_adjustMiniComic = function (notif) {
        this.miniComicController.moveMiniComic(notif.args.miniComic);
        this.playerController.adjustIncome(notif.args.player, notif.args.incomeChange);
    };
    /**
     * Handle 'adjustMoney' notification
     *
     * Notif args:
     * - player: player object
     * - amount: amount to adjust by
     *
     * @param notif
     */
    GameBody.prototype.notif_adjustMoney = function (notif) {
        this.playerController.adjustMoney(notif.args.player, notif.args.amount);
    };
    GameBody.prototype.notif_assignComic = function (notif) {
        this.cardController.slideCardToPlayerMat(notif.args.player, notif.args.card, notif.args.slot);
        if (notif.args.spentIdeas > 0) {
            this.playerController.adjustIdeas(notif.args.player, notif.args.card.genre, -notif.args.spentIdeas);
        }
        this.playerController.adjustHand(notif.args.player, -1);
    };
    GameBody.prototype.notif_assignCreative = function (notif) {
        this.cardController.slideCardToPlayerMat(notif.args.player, notif.args.card, notif.args.slot);
        this.playerController.adjustMoney(notif.args.player, -notif.args.cost);
        this.playerController.adjustHand(notif.args.player, -1);
    };
    /**
     * Handle'completeSetup' notification
     *
     * Notif args:
     * - artistCards: {deck: array, supply: array}
     * - writerCards: {deck: array, supply: array}
     *
     * @param notif
     */
    GameBody.prototype.notif_completeSetup = function (notif) {
        this.cardController.setupCards(notif.args.artistCards.deck);
        this.cardController.setupCards(notif.args.writerCards.deck);
        this.cardController.setupCards(notif.args.comicCards.deck);
        this.cardController.setupCards(notif.args.artistCards.supply);
        this.cardController.setupCards(notif.args.writerCards.supply);
        this.cardController.setupCards(notif.args.comicCards.supply);
    };
    /**
     * Handle 'developComic' notification
     *
     * Notif args:
     * - comic: comic card
     *
     * @param notif
     */
    GameBody.prototype.notif_developComic = function (notif) {
        this.cardController.slideCardToPlayerHand(notif.args.comic);
        this.playerController.adjustHand(notif.args.player, 1);
    };
    /**
     * Handle 'developComicPrivate' notification
     *
     * Notif args:
     * - comic: comic card
     *
     * @param notif
     */
    GameBody.prototype.notif_developComicPrivate = function (notif) {
        this.cardController.slideCardToPlayerHand(notif.args.comic);
        this.playerController.adjustHand(notif.args.player, 1);
    };
    /**
     * Handle 'discardCard' notification
     *
     * Notif args:
     * - card: card to discard
     * - player: player object
     *
     * @param notif
     */
    GameBody.prototype.notif_discardCard = function (notif) {
        this.cardController.discardCard(notif.args.card, notif.args.player.id);
        this.playerController.adjustHand(notif.args.player, -1);
    };
    /**
     * Handle 'discardCardFromDeck' notification
     *
     * Notif args:
     * - card: card to discard
     *
     * @param notif
     */
    GameBody.prototype.notif_discardCardFromDeck = function (notif) {
        this.cardController.discardCardFromDeck(notif.args.card);
    };
    /**
     * Handle 'flipCalendarTiles' notification
     *
     * Notif args:
     * - flippedTiles: array of flipped tiles
     *
     * @param notif
     */
    GameBody.prototype.notif_flipCalendarTiles = function (notif) {
        this.calendarController.flipCalendarTiles(notif.args.flippedTiles);
    };
    /**
     * Handle 'flipSalesOrders' notification
     *
     * Notif args:
     * - flippedSalesOrders: array of flipped sales orders
     *
     * @param notif
     */
    GameBody.prototype.notif_flipSalesOrders = function (notif) {
        this.salesOrderController.flipSalesOrders(notif.args.flippedSalesOrders);
    };
    /**
     * Handle 'gainIdeaFromBoard' notification
     *
     * Notif args:
     * - player: player object
     * - genre: genre of idea
     *
     * @param notif
     */
    GameBody.prototype.notif_gainIdeaFromBoard = function (notif) {
        this.ideaController.gainIdeaFromBoard(notif.args.player.id, notif.args.genre);
        this.playerController.adjustIdeas(notif.args.player, notif.args.genre, 1);
    };
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
    GameBody.prototype.notif_gainIdeaFromHiringCreative = function (notif) {
        this.ideaController.gainIdeaFromHiringCreative(notif.args.player.id, notif.args.genre, notif.args.card.id);
        this.playerController.adjustIdeas(notif.args.player, notif.args.genre, 1);
    };
    /**
     * Handle 'gainIdeaFromSupply' notification
     *
     * Notif args:
     * - player: player object
     * - genre: genre of idea
     *
     * @param notif
     */
    GameBody.prototype.notif_gainIdeaFromSupply = function (notif) {
        this.ideaController.gainIdeaFromSupply(notif.args.player.id, notif.args.genre);
        this.playerController.adjustIdeas(notif.args.player, notif.args.genre, 1);
    };
    /**
     * Handle 'gainStartingComic' notification
     *
     * Notif args:
     * - player: player object
     * - comic_card: comic card
     *
     * @param notif
     */
    GameBody.prototype.notif_gainStartingComic = function (notif) {
        this.cardController.gainStartingComic(notif.args.comic_card);
        this.playerController.adjustHand(notif.args.player, 1);
    };
    /**
     * Handle 'gainStartingComicPrivate' notification
     *
     * Notif args:
     * - comic_card: comic card
     *
     * @param notif
     */
    GameBody.prototype.notif_gainStartingComicPrivate = function (notif) {
        this.cardController.gainStartingComic(notif.args.comic_card);
        this.playerController.adjustHand(notif.args.player, 1);
    };
    /**
     * Handle 'gainStartingIdea' notification
     *
     * Notif args:
     * - player: player object
     * - genre: genre of idea
     *
     * @param notif
     */
    GameBody.prototype.notif_gainStartingIdea = function (notif) {
        this.ideaController.gainStartingIdea(notif.args.player.id, notif.args.genre);
        this.playerController.adjustIdeas(notif.args.player, notif.args.genre, 1);
    };
    /**
     * Handle 'gainTicket' notification
     *
     * Notif args:
     * - player: player object
     *
     * @param notif
     */
    GameBody.prototype.notif_gainTicket = function (notif) {
        this.ticketController.gainTicket(notif.args.player);
        this.playerController.adjustTickets(notif.args.player, 1);
    };
    /**
     * Handle 'hireCreative' notification
     *
     * Notif args:
     * - card: card to hire
     * - player: player object
     *
     * @param notif
     */
    GameBody.prototype.notif_hireCreative = function (notif) {
        this.cardController.slideCardToPlayerHand(notif.args.card);
        this.playerController.adjustHand(notif.args.player, 1);
    };
    /**
     * Handle 'hireCreativePrivate' notification
     *
     * Notif args:
     * - card: card to hire
     *
     * @param notif
     */
    GameBody.prototype.notif_hireCreativePrivate = function (notif) {
        this.cardController.slideCardToPlayerHand(notif.args.card);
        this.playerController.adjustHand(notif.args.player, 1);
    };
    /**
     * Handle 'moveMiniComic' notification
     *
     * Notif args:
     * - miniComic: mini comic object
     * - player: player object
     * - incomeChange: amount to adjust income by
     *
     * @param notif
     */
    GameBody.prototype.notif_moveMiniComic = function (notif) {
        this.miniComicController.moveMiniComic(notif.args.miniComic);
        this.playerController.adjustIncome(notif.args.player, notif.args.incomeChange);
    };
    GameBody.prototype.notif_masteryTokenClaimed = function (notif) {
        this.masteryController.moveMasteryToken(notif.args.masteryToken);
    };
    /**
     * Handle 'placeEditor' notification
     *
     * Notif args:
     * - editor: editor object
     * - space: space to place editor
     *
     * @param notif
     */
    GameBody.prototype.notif_placeEditor = function (notif) {
        this.editorController.moveEditorToActionSpace(notif.args.editor, notif.args.space);
    };
    GameBody.prototype.notif_placeUpgradeCube = function (notif) {
        this.playerController.moveUpgradeCube(notif.args.player, notif.args.cubeMoved, notif.args.actionKey);
    };
    /**
     * Handle'reshuffleDiscardPile' notification
     *
     * Notif args:
     * - deck: array of cards in deck
     *
     * @param notif
     */
    GameBody.prototype.notif_reshuffleDiscardPile = function (notif) {
        this.cardController.setupCards(notif.args.deck);
    };
    /**
     * Handle 'setupMoney' notification
     *
     * Notif args:
     * - player: player object
     * - money: amount of money to set
     *
     * @param {object} notif - notification data
     */
    GameBody.prototype.notif_setupMoney = function (notif) {
        this.playerController.adjustMoney(notif.args.player, notif.args.money);
    };
    /**
     * Handle 'takeRoyalties' notification
     *
     * Notif args:
     * - player: player object
     * - amount: amount to adjust by
     *
     * @param notif
     */
    GameBody.prototype.notif_takeRoyalties = function (notif) {
        this.playerController.adjustMoney(notif.args.player, notif.args.amount);
    };
    return GameBody;
}(GameBasics));
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * GameState.ts
 *
 * Class that holds all game states
 *
 */
var GameState = /** @class */ (function () {
    function GameState(game) {
        this.checkHandSize = new CheckHandSize(game);
        this.completeSetup = new CompleteSetup(game);
        this.gameEnd = new GameEnd(game);
        this.gameSetup = new GameSetup(game);
        this.nextPlayer = new NextPlayer(game);
        this.nextPlayerSetup = new NextPlayerSetup(game);
        this.performDevelop = new PerformDevelop(game);
        this.performHire = new PerformHire(game);
        this.performIdeas = new PerformIdeas(game);
        this.performPrint = new PerformPrint(game);
        this.performPrintBonus = new PerformPrintBonus(game);
        this.performPrintFulfillOrders = new PerformPrintFulfillOrders(game);
        this.performPrintMastery = new PerformPrintMastery(game);
        this.performPrintUpgrade = new PerformPrintUpgrade(game);
        this.performRoyalties = new PerformRoyalties(game);
        this.performSales = new PerformSales(game);
        this.playerSetup = new PlayerSetup(game);
        this.playerTurn = new PlayerTurn(game);
        this.startNewRound = new StartNewRound(game);
    }
    return GameState;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * define.ts
 *
 */
// @ts-nocheck
define([
    "dojo",
    "dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/stock",
], function (dojo, declare) {
    return declare("bgagame.ageofcomics", ebg.core.gamegui, new GameBody());
});
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * CalendarController.ts
 *
 * Handles all front end interactions with the calendar
 *
 */
var CalendarController = /** @class */ (function () {
    function CalendarController(ui) {
        this.ui = ui;
    }
    /**
     * Set up the calendar by creating the tiles
     *
     * @param calendarTiles - the tiles to create
     */
    CalendarController.prototype.setupCalendar = function (calendarTiles) {
        for (var key in calendarTiles) {
            this.createCalendarTile(calendarTiles[key]);
        }
    };
    /**
     * Create a calendar tile
     *
     * @param calendarTile - the tile to create
     */
    CalendarController.prototype.createCalendarTile = function (calendarTile) {
        var calendarTileDiv = '<div id="aoc-calender-tile-' +
            calendarTile.id +
            '" class="aoc-calendar-tile ' +
            calendarTile.cssClass +
            '"></div>';
        this.ui.createHtml(calendarTileDiv, "aoc-calendar-slot-" + calendarTile.position);
    };
    /**
     * Flip a calendar tile face-up
     *
     * @param calendarTile - the tile to flip
     */
    CalendarController.prototype.flipCalendarTile = function (calendarTile) {
        var calendarTileDiv = dojo.byId("aoc-calender-tile-" + calendarTile.id);
        dojo.removeClass(calendarTileDiv, "aoc-calendar-tile-facedown");
        dojo.addClass(calendarTileDiv, calendarTile.cssClass);
    };
    /**
     * Flip multiple calendar tiles face-up
     *
     * @param calendarTiles - the tiles to flip
     */
    CalendarController.prototype.flipCalendarTiles = function (calendarTiles) {
        for (var key in calendarTiles) {
            this.flipCalendarTile(calendarTiles[key]);
        }
    };
    return CalendarController;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * CardController.ts
 *
 * Handles all front end interactions with the cards
 *
 */
var CardController = /** @class */ (function () {
    function CardController(ui) {
        this.ui = ui;
    }
    /**
     * Setup all cards
     *
     * @param cards - the cards to setup
     */
    CardController.prototype.setupCards = function (cards) {
        // Sort cards by locationArg
        cards.sort(function (a, b) {
            return a.locationArg - b.locationArg;
        });
        // Create each card
        for (var i in cards) {
            var card = cards[i];
            this.createNewCard(card);
        }
    };
    /**
     * Create a new card
     *
     * @param card - the card to create
     * @param location - the location to create the card in
     */
    CardController.prototype.createNewCard = function (card, location) {
        // Create the card div
        var cardDiv = this.createCardDiv(card);
        // If a location is provided, create the card in that location
        if (location) {
            this.ui.createHtml(cardDiv, location);
            return;
        }
        // Otherwise, create the card in the appropriate location based on the card's location attribute
        switch (card.location) {
            case globalThis.LOCATION_DECK:
                this.ui.createHtml(cardDiv, "aoc-" + card.type + "-deck");
                break;
            case globalThis.LOCATION_DISCARD:
                this.ui.createHtml(cardDiv, "aoc-" + card.type + "s-discard");
                break;
            case globalThis.LOCATION_HAND:
                this.ui.createHtml(cardDiv, "aoc-hand-" + card.playerId);
                break;
            case globalThis.LOCATION_SUPPLY:
                this.ui.createHtml(cardDiv, "aoc-" + card.type + "s-available");
                break;
            case globalThis.LOCATION_PLAYER_MAT:
                this.ui.createHtml(cardDiv, "aoc-" + card.type + "-slot-" + card.locationArg + "-" + card.playerId);
                break;
        }
    };
    /**
     * Create a new element
     *
     * @param card - the card to create
     * @returns the card div
     */
    CardController.prototype.createCardDiv = function (card) {
        var id = "aoc-card-" + card.id;
        var css = this.getCardDivCss(card);
        var order = card.locationArg;
        return "<div id=\"".concat(id, "\" class=\"").concat(css, "\" order=\"").concat(order, "\"></div>");
    };
    /**
     * Get the css class for a card based on its type
     *
     * @param card - the card to get the css class for
     * @returns the css class
     */
    CardController.prototype.getCardDivCss = function (card) {
        return ("aoc-card " +
            card.cssClass +
            " " +
            this.getCardTypeCss(card.type) +
            " " +
            card.cssClass);
    };
    /**
     * Get the css class for a card based on its type
     *
     * @param cardType - the card type to get the css class for
     * @returns the css class
     */
    CardController.prototype.getCardTypeCss = function (cardType) {
        switch (cardType) {
            case "artist":
                return "aoc-creative-card";
            case "writer":
                return "aoc-creative-card";
            case "comic":
                return "aoc-comic-card";
            case "ripoff":
                return "aoc-ripoff-card";
        }
    };
    /**
     * Moves card from a player's hand to the appropriate discard pile.
     *
     * @param card - the card to discard
     * @param playerId - the player who is discarding the card
     */
    CardController.prototype.discardCard = function (card, playerId) {
        // Get the card div
        var cardDiv = dojo.byId("aoc-card-" + card.id);
        // Move card out of overlay to allow animation
        dojo.place(cardDiv, "aoc-player-area-right-" + playerId);
        // If the card is face down, flip it face up
        if (cardDiv.classList.contains(card.facedownClass)) {
            cardDiv.classList.remove(card.facedownClass);
            cardDiv.classList.add(card.baseClass);
        }
        // Get the discard pile for the card's type
        var discardDiv = dojo.byId("aoc-" + card.type + "s-discard");
        // Create the animation
        var animation = gameui.slideToObject(cardDiv, discardDiv, 500);
        dojo.connect(animation, "onEnd", function () {
            // After animation ends, remove styling added by animation and place in new parent div
            dojo.removeAttr(cardDiv, "style");
            dojo.place(cardDiv, discardDiv);
        });
        // Play the animation
        animation.play();
    };
    /**
     * Moves card from the top of a deck to the appropriate discard pile.
     *
     * @param card - the card to discard
     */
    CardController.prototype.discardCardFromDeck = function (card) {
        // Get the card div
        var cardDiv = dojo.byId("aoc-card-" + card.id);
        // Flip the card face-up
        cardDiv.classList.remove(card.facedownClass);
        cardDiv.classList.add(card.baseClass);
        // Get the discard pile for the card's type
        var discardDiv = dojo.byId("aoc-" + card.type + "s-discard");
        // Create the animation
        var animation = gameui.slideToObject(cardDiv, discardDiv, 500);
        dojo.connect(animation, "onEnd", function () {
            // After animation ends, remove styling added by animation and place in new parent div
            dojo.removeAttr(cardDiv, "style");
            dojo.place(cardDiv, discardDiv);
        });
        // Play the animation
        animation.play();
    };
    /**
     * A player gains their starting comic card
     *
     * @param card - the card to gain
     */
    CardController.prototype.gainStartingComic = function (card) {
        // Get the location of the card selection area
        var location = "aoc-select-starting-comic-" + card.genre;
        // Create the card
        this.createNewCard(card, location);
        // Slide the card to the player's hand
        this.slideCardToPlayerHand(card);
    };
    CardController.prototype.getCardTypeForMatSlot = function (card) {
        switch (card.typeId) {
            case globalThis.CARD_TYPE_ARTIST:
                return "artist";
            case globalThis.CARD_TYPE_WRITER:
                return "writer";
            case globalThis.CARD_TYPE_COMIC:
                return "comic";
            case globalThis.CARD_TYPE_RIPOFF:
                return "comic";
        }
    };
    /**
     * Moves a card element to a player's hand
     *
     * @param card - the card to move
     */
    CardController.prototype.slideCardToPlayerHand = function (card) {
        // Get the card div
        var cardDiv = dojo.byId("aoc-card-" + card.id);
        // Set the card face up or face down depeding on the card's css class
        var facedownCss = card.facedownClass;
        var baseCss = card.baseClass;
        if (cardDiv.classList.contains(facedownCss) &&
            card.cssClass !== facedownCss) {
            cardDiv.classList.remove(facedownCss);
            cardDiv.classList.add(card.cssClass);
        }
        if (!cardDiv.classList.contains(facedownCss) &&
            card.cssClass === facedownCss) {
            cardDiv.classList.remove(baseCss);
            cardDiv.classList.add(facedownCss);
        }
        // Add an order attribute to the card div
        dojo.setAttr(cardDiv, "order", card.locationArg);
        // Get the hand div
        var handDiv = dojo.byId("aoc-hand-" + card.playerId);
        // Get the card divs in the hand
        var cardsInHand = dojo.query(".aoc-card", handDiv);
        // Get the card div to the right of the new card's location
        var cardToRightOfNewCard = null;
        cardsInHand.forEach(function (cardInHand) {
            if (cardToRightOfNewCard == null &&
                cardInHand.getAttribute("order") > cardDiv.getAttribute("order")) {
                cardToRightOfNewCard = cardInHand;
            }
        });
        // Create the animation
        var animation = gameui.slideToObject(cardDiv, handDiv, 500);
        dojo.connect(animation, "onEnd", function () {
            // After animation ends, remove styling added by animation and place in new parent div
            dojo.removeAttr(cardDiv, "style");
            if (cardToRightOfNewCard == null) {
                dojo.place(cardDiv, handDiv);
            }
            else {
                dojo.place(cardDiv, cardToRightOfNewCard, "before");
            }
        });
        // Play the animation
        animation.play();
    };
    CardController.prototype.slideCardToPlayerMat = function (player, card, slot) {
        // If card is ripoff, create div
        if (card.typeId === globalThis.CARD_TYPE_RIPOFF) {
            this.createNewCard(card, "aoc-overall");
        }
        // Get the card div
        var cardDiv = dojo.byId("aoc-card-" + card.id);
        var cardType = this.getCardTypeForMatSlot(card);
        // Set the card faceup
        if (cardDiv.classList.contains(card.facedownClass)) {
            cardDiv.classList.remove(card.facedownClass);
            cardDiv.classList.add(card.baseClass);
        }
        // Get the player mat slot div
        var slotDiv = dojo.byId("aoc-" + cardType + "-slot-" + slot + "-" + player.id);
        // Create the animation
        var animation = gameui.slideToObject(cardDiv, slotDiv, 1000);
        dojo.connect(animation, "onEnd", function () {
            // After animation ends, remove styling added by animation and place in new parent div
            dojo.removeAttr(cardDiv, "style");
            dojo.place(cardDiv, slotDiv);
        });
        // Play the animation
        animation.play();
    };
    return CardController;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * EditorController.ts
 *
 * Handles all front end interactions with the editors
 *
 */
var EditorController = /** @class */ (function () {
    function EditorController(ui) {
        this.ui = ui;
    }
    /**
     * Setup all editors
     *
     * @param editors - the editors to setup
     */
    EditorController.prototype.setupEditors = function (editors) {
        for (var key in editors) {
            this.createEditor(editors[key]);
        }
    };
    /**
     * Create a new editor
     *
     * @param editor - the editor to create
     */
    EditorController.prototype.createEditor = function (editor) {
        // Create the editor div
        var editorDiv = '<div id="aoc-editor-' +
            editor.id +
            '" class="aoc-editor ' +
            editor.cssClass +
            '"></div>';
        // Place the editor in the appropriate location
        if (editor.locationId == globalThis.LOCATION_EXTRA_EDITOR) {
            var color = this.ui.getPlayerColorAsString(editor.color);
            this.ui.createHtml(editorDiv, "aoc-extra-editor-space-" + color);
        }
        else if (editor.locationId == globalThis.LOCATION_PLAYER_AREA) {
            var color = this.ui.getPlayerColorAsString(editor.color);
            this.ui.createHtml(editorDiv, "aoc-editor-container-" + editor.playerId);
        }
        else {
            var actionSpaceDiv = dojo.query("[space$=" + editor.locationId + "]")[0];
            this.ui.createHtml(editorDiv, actionSpaceDiv.id);
        }
    };
    /**
     * Move an editor to an action space
     *
     * @param editor - the editor to move
     * @param actionSpace - the action space to move the editor to
     */
    EditorController.prototype.moveEditorToActionSpace = function (editor, actionSpace) {
        // Get the editor div
        var editorDiv = dojo.byId("aoc-editor-" + editor.id);
        // Get the action space div
        var actionSpaceDiv = dojo.query("[space$=" + actionSpace + "]")[0];
        // Create the animation to move the editor to the action space
        var animation = gameui.slideToObject(editorDiv, actionSpaceDiv);
        dojo.connect(animation, "onEnd", function () {
            // After animation, attach editor to new parent div
            gameui.attachToNewParent(editorDiv, actionSpaceDiv);
        });
        // Play the animation
        animation.play();
    };
    return EditorController;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * GameController.ts
 *
 * Handles general game logic on front-end
 *
 */
var GameController = /** @class */ (function () {
    function GameController(ui) {
        this.ui = ui;
    }
    /**
     * Set up game
     * @param {object} gamedata - current game data used to initialize UI
     */
    GameController.prototype.setup = function (gamedata) {
        this.createNeededGameElements(gamedata);
    };
    /**
     * Create:
     *  - game status panel
     *  - show chart container
     *  - chart
     * @param {object} gamedata - current game data used to initialize UI
     */
    GameController.prototype.createNeededGameElements = function (gamedata) {
        this.createGameStatusPanelHtml();
        this.createShowChartContainerHtml();
        this.createChartHtml(gamedata.playerInfo);
        this.createOnClickEvents();
    };
    /**
     * Creates the game status panel above the player board panels
     */
    GameController.prototype.createGameStatusPanelHtml = function () {
        var gameStatusPanelHtml = '<div id="aoc-game-status-panel" class="player-board"><div id="aoc-game-status" class="player_board_content"><div id="aoc-game-status-mastery-container" class="aoc-game-status-row"></div><div id="aoc-button-row" class="aoc-game-status-row"><a id="aoc-show-chart-button" class="aoc-status-button" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-chart-simple"></i></a><a id="aoc-carousel-button" class="aoc-status-button" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-arrows-left-right-to-line"></i></a><a id="aoc-list-button" class="aoc-status-button" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-list"></i></a></div></div></div>';
        this.ui.createHtml(gameStatusPanelHtml, "player_boards");
    };
    /**
     * Creates the container for the chart
     */
    GameController.prototype.createShowChartContainerHtml = function () {
        var showChartContainerHtml = '<div id="aoc-show-chart-container"><div id="aoc-show-chart-underlay"></div><div id="aoc-show-chart-wrapper"></div></div>';
        this.ui.createHtml(showChartContainerHtml, "overall-content");
    };
    /**
     * Creates the comics chart - only show tracks for players in the game
     * @param {object} players - player info
     */
    GameController.prototype.createChartHtml = function (players) {
        var chartWidth = 87 + players.length * 71;
        var chartHtml = '<div id="aoc-show-chart" style="width: ' +
            chartWidth +
            '"><a id="aoc-show-chart-close" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-square-xmark fa6-2x aria-hidden="true"></i></a><div id="aoc-chart" class="aoc-board-section"><div id="aoc-chart-start" class="aoc-board-image aoc-chart-start"></div>';
        for (var key in players) {
            var player = players[key];
            chartHtml +=
                '<div id="aoc-chart-' +
                    player.id +
                    '" class="aoc-board-image aoc-chart-' +
                    player.colorAsText +
                    '"></div>';
        }
        chartHtml +=
            '<div id="aoc-chart-end" class="aoc-board-image aoc-chart-end"></div></div></div>';
        this.ui.createHtml(chartHtml, "aoc-show-chart-wrapper");
        for (var key in players) {
            var player = players[key];
            this.createChartSpacesHtml(player);
        }
    };
    GameController.prototype.createChartSpacesHtml = function (player) {
        var space = 0;
        while (space < 11) {
            var chartFanSpaceHtml = '<div id="aoc-chart-space-' +
                player.id +
                "-" +
                space +
                '" class="aoc-chart-space aoc-chart-space-' +
                space +
                '"></div>';
            this.ui.createHtml(chartFanSpaceHtml, "aoc-chart-" + player.id);
            space++;
        }
    };
    /**
     * Creates the on click events for the game
     *
     * - show chart
     * - hide chart
     * - carousel view
     * - list view
     * - next player
     * - previous player
     */
    GameController.prototype.createOnClickEvents = function () {
        dojo.connect($("aoc-show-chart-button"), "onclick", this, "showChart");
        dojo.connect($("aoc-show-chart-close"), "onclick", this, "hideChart");
        dojo.connect($("aoc-carousel-button"), "onclick", this, "carouselView");
        dojo.connect($("aoc-list-button"), "onclick", this, "listView");
        dojo.query(".fa6-circle-right").connect("onclick", this, "nextPlayer");
        dojo.query(".fa6-circle-left").connect("onclick", this, "previousPlayer");
    };
    /**
     * Show chart
     */
    GameController.prototype.showChart = function () {
        dojo.style("aoc-show-chart-container", "display", "block");
    };
    /**
     * Hide chart
     */
    GameController.prototype.hideChart = function () {
        dojo.style("aoc-show-chart-container", "display", "none");
    };
    /**
     * Switch to carousel view
     */
    GameController.prototype.carouselView = function () {
        var playersSection = dojo.query("#aoc-players-section")[0];
        for (var i = 1; i < playersSection.children.length; i++) {
            var playerSection = playersSection.children[i];
            if (!dojo.hasClass(playerSection, "aoc-hidden")) {
                dojo.toggleClass(playerSection, "aoc-hidden");
            }
        }
        var arrows = dojo.query(".aoc-arrow");
        arrows.forEach(function (arrow) {
            if (dojo.hasClass(arrow, "aoc-hidden")) {
                dojo.toggleClass(arrow, "aoc-hidden");
            }
        });
        this.ui.adaptViewportSize();
    };
    /**
     * Switch to list view
     */
    GameController.prototype.listView = function () {
        var playersSection = dojo.query("#aoc-players-section")[0];
        for (var i = 0; i < playersSection.children.length; i++) {
            var playerSection = playersSection.children[i];
            if (dojo.hasClass(playerSection, "aoc-hidden")) {
                dojo.toggleClass(playerSection, "aoc-hidden");
            }
        }
        var arrows = dojo.query(".aoc-arrow");
        arrows.forEach(function (arrow) {
            if (!dojo.hasClass(arrow, "aoc-hidden")) {
                dojo.toggleClass(arrow, "aoc-hidden");
            }
        });
        this.ui.adaptViewportSize();
    };
    /**
     * While in carousel view, switch to next player
     */
    GameController.prototype.nextPlayer = function () {
        var visiblePlayerSection = dojo.query(".aoc-player-background-panel:not(.aoc-hidden)")[0];
        var visiblePlayerId = visiblePlayerSection.id;
        var playersSection = dojo.query("#aoc-players-section")[0];
        for (var i = 0; i < playersSection.children.length; i++) {
            var playerSection = playersSection.children[i];
            if (playerSection.id == visiblePlayerId) {
                if (i == playersSection.children.length - 1) {
                    var nextPlayerSection = playersSection.children[0];
                }
                else {
                    var nextPlayerSection = playersSection.children[i + 1];
                }
            }
        }
        dojo.toggleClass(visiblePlayerSection, "aoc-hidden");
        dojo.toggleClass(nextPlayerSection, "aoc-hidden");
    };
    /**
     * While in carousel view, switch to previous player
     */
    GameController.prototype.previousPlayer = function () {
        var visiblePlayerSection = dojo.query(".aoc-player-background-panel:not(.aoc-hidden)")[0];
        var visiblePlayerId = visiblePlayerSection.id;
        var playersSection = dojo.query("#aoc-players-section")[0];
        for (var i = 0; i < playersSection.children.length; i++) {
            var playerSection = playersSection.children[i];
            if (playerSection.id == visiblePlayerId) {
                if (i == 0) {
                    var previousPlayerSection = playersSection.children[playersSection.children.length - 1];
                }
                else {
                    var previousPlayerSection = playersSection.children[i - 1];
                }
            }
        }
        dojo.toggleClass(visiblePlayerSection, "aoc-hidden");
        dojo.toggleClass(previousPlayerSection, "aoc-hidden");
    };
    return GameController;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * IdeaController.ts
 *
 * Handles idea token logic on front-end
 *
 */
var IdeaController = /** @class */ (function () {
    function IdeaController(ui) {
        this.ui = ui;
    }
    /**
     * Set up idea tokens
     * @param {object} ideaSpaceContents - current idea token data used to initialize UI
     */
    IdeaController.prototype.setupIdeas = function (ideaSpaceContents) {
        this.createIdeaTokensOnBoard(ideaSpaceContents);
    };
    /**
     * Creates the idea tokens on the board
     * @param {object} ideasSpaceContents - ideas space contents
     */
    IdeaController.prototype.createIdeaTokensOnBoard = function (ideasSpaceContents) {
        for (var key in ideasSpaceContents) {
            var genreSpace = ideasSpaceContents[key];
            this.createIdeaTokenOnBoard(key, genreSpace);
        }
    };
    /**
     * Creates an idea token on the board
     *
     * @param genreId - the genre id of the idea token
     * @param exists - whether or not the idea token exists on the board
     */
    IdeaController.prototype.createIdeaTokenOnBoard = function (genreId, exists) {
        if (exists == 1) {
            var genre = this.ui.getGenreName(genreId);
            var ideaTokenDiv = '<div id="aoc-idea-token-' +
                genre +
                '" class="aoc-idea-token aoc-idea-token-' +
                genre +
                '"></div>';
            this.ui.createHtml(ideaTokenDiv, "aoc-action-ideas-" + genre);
        }
    };
    /**
     * Creates an idea token on a card
     *
     * @param genre - the genre of the idea token
     * @param cardId - the card id of the card the idea token is on
     */
    IdeaController.prototype.createIdeaTokenOnCard = function (genre, cardId) {
        var randomId = Math.floor(Math.random() * 1000000);
        var ideaTokenDiv = '<div id="' +
            randomId +
            '" class="aoc-idea-token aoc-idea-token-' +
            genre +
            '" style="position:relative;z-index:1000;"></div>';
        return this.ui.createHtml(ideaTokenDiv, "aoc-card-" + cardId);
    };
    /**
     * Creates an idea token on the supply
     *
     * @param genre - the genre of the idea token
     */
    IdeaController.prototype.createIdeaTokenOnSupply = function (genre) {
        var randomId = Math.floor(Math.random() * 1000000);
        var ideaTokenDiv = '<div id="' +
            randomId +
            '" class="aoc-idea-token aoc-idea-token-' +
            genre +
            '" style="position:relative;z-index:1000;"></div>';
        return this.ui.createHtml(ideaTokenDiv, "aoc-select-supply-idea-token-" + genre);
    };
    /**
     * Creates an idea token on the select start ideas container
     *
     * @param genre - the genre of the idea token
     */
    IdeaController.prototype.createStartingIdeaToken = function (genre) {
        var randomId = Math.floor(Math.random() * 1000000);
        var ideaTokenDiv = '<div id="' +
            randomId +
            '" class="aoc-idea-token aoc-idea-token-' +
            genre +
            '" style="position:relative;z-index:1000;"></div>';
        return this.ui.createHtml(ideaTokenDiv, "aoc-select-starting-idea-" + genre);
    };
    /**
     * Moves an idea token from the board to a player's panel
     *
     * @param playerId - the player id of the player who gained the idea token
     * @param genre - the genre of the idea token
     */
    IdeaController.prototype.gainIdeaFromBoard = function (playerId, genre) {
        var ideaTokenDiv = dojo.byId("aoc-idea-token-" + genre);
        var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
        gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
    };
    /**
     * Create an idea token on a card and move it to a player's panel
     *
     * @param playerId - the player id of the player who gained the idea token
     * @param genre - the genre of the idea token
     * @param cardId - the card id of the card the idea token is on
     */
    IdeaController.prototype.gainIdeaFromHiringCreative = function (playerId, genre, cardId) {
        var ideaTokenDiv = this.createIdeaTokenOnCard(genre, cardId);
        var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
        gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
    };
    /**
     * Create an idea token on the supply and move it to a player's panel
     *
     * @param playerId - the player id of the player who gained the idea token
     * @param genre - the genre of the idea token
     */
    IdeaController.prototype.gainIdeaFromSupply = function (playerId, genre) {
        var ideaTokenDiv = this.createIdeaTokenOnSupply(genre);
        var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
        gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
    };
    /**
     * Create an idea token on the select start ideas container and move it to a player's panel
     *
     * @param playerId - the player id of the player who gained the idea token
     * @param genre - the genre of the idea token
     */
    IdeaController.prototype.gainStartingIdea = function (playerId, genre) {
        var ideaTokenDiv = this.createStartingIdeaToken(genre);
        var playerPanelIcon = dojo.byId("aoc-player-" + genre + "-" + playerId);
        gameui.slideToObjectAndDestroy(ideaTokenDiv, playerPanelIcon, 1000);
    };
    return IdeaController;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * MasteryController.ts
 *
 * Handles mastery token logic on front-end
 *
 */
var MasteryController = /** @class */ (function () {
    function MasteryController(ui) {
        this.ui = ui;
    }
    /**
     * Set up mastery tokens
     * @param {object} masteryTokens - current mastery token data used to initialize UI
     */
    MasteryController.prototype.setupMasteryTokens = function (masteryTokens) {
        for (var key in masteryTokens) {
            this.createMasteryToken(masteryTokens[key]);
        }
    };
    /**
     * Creates a mastery token
     * @param {object} masteryToken - mastery token data
     */
    MasteryController.prototype.createMasteryToken = function (masteryToken) {
        var masteryTokenDiv = '<div id="aoc-mastery-token-' +
            masteryToken.id +
            '" class="aoc-mastery-token aoc-mastery-token-' +
            masteryToken.genre +
            '"></div>';
        if (masteryToken.playerId == 0) {
            this.ui.createHtml(masteryTokenDiv, "aoc-game-status-mastery-container");
        }
        else {
            this.ui.createHtml(masteryTokenDiv, "aoc-mastery-container-" + masteryToken.playerId);
        }
    };
    MasteryController.prototype.moveMasteryToken = function (masteryToken) {
        var masteryTokenElement = dojo.byId("aoc-mastery-token-" + masteryToken.id);
        var targetElement = dojo.byId("aoc-mastery-container-" + masteryToken.playerId);
        var animation = this.ui.slideToObject(masteryTokenElement, targetElement, 500);
        dojo.connect(animation, "onEnd", function () {
            dojo.removeAttr(masteryTokenElement, "style");
            dojo.place(masteryTokenElement, targetElement);
        });
        animation.play();
    };
    return MasteryController;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * MiniComicController.ts
 *
 * Handles mini comic logic on front-end
 *
 */
var MiniComicController = /** @class */ (function () {
    function MiniComicController(ui) {
        this.ui = ui;
    }
    /**
     * Set up mini comics
     * @param {object} miniComics - current mini comic data used to initialize UI
     */
    MiniComicController.prototype.setupMiniComics = function (miniComics) {
        for (var key in miniComics) {
            this.createMiniComic(miniComics[key]);
        }
    };
    /**
     * Creates a mini comic
     * @param {object} miniComic - mini comic data
     */
    MiniComicController.prototype.createMiniComic = function (miniComic) {
        var miniComicDiv = '<div id="aoc-mini-comic-' +
            miniComic.id +
            '" class="aoc-mini-comic ' +
            miniComic.cssClass +
            '"></div>';
        if (miniComic.location == globalThis.LOCATION_SUPPLY) {
            this.ui.createHtml(miniComicDiv, "aoc-mini-" + miniComic.type + "s-" + miniComic.genre);
        }
        if (miniComic.location == globalThis.LOCATION_CHART) {
            var space = miniComic.fans > 10 ? miniComic.fans - 10 : miniComic.fans;
            this.ui.createHtml(miniComicDiv, "aoc-chart-space-" + miniComic.playerId + "-" + space);
        }
    };
    MiniComicController.prototype.moveMiniComic = function (miniComic) {
        console.log("moveMiniComic");
        var miniComicDiv = dojo.byId("aoc-mini-comic-" + miniComic.id);
        var chartSpaceDiv = dojo.byId("aoc-chart-space-" + miniComic.playerId + "-" + miniComic.fans);
        var animation = gameui.slideToObject(miniComicDiv, chartSpaceDiv, 500);
        dojo.connect(animation, "onEnd", function () {
            dojo.removeAttr(miniComicDiv, "style");
            dojo.place(miniComicDiv, chartSpaceDiv);
        });
        animation.play();
    };
    MiniComicController.prototype.moveMiniComicToChart = function (miniComic) {
        var miniComicDiv = dojo.byId("aoc-mini-comic-" + miniComic.id);
        var chartSpaceDiv = dojo.byId("aoc-chart-space-" + miniComic.playerId + "-" + miniComic.fans);
        var animation = gameui.slideToObject(miniComicDiv, chartSpaceDiv, 500);
        dojo.connect(animation, "onEnd", function () {
            dojo.removeAttr(miniComicDiv, "style");
            dojo.place(miniComicDiv, chartSpaceDiv);
        });
        animation.play();
    };
    return MiniComicController;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PlayerController.ts
 *
 * Handles player logic on front-end
 *
 */
var PlayerController = /** @class */ (function () {
    function PlayerController(ui) {
        this.ui = ui;
        this.actions = [];
        this.actions[1] = "hire";
        this.actions[2] = "develop";
        this.actions[3] = "ideas";
        this.actions[4] = "print";
        this.actions[5] = "royalties";
        this.actions[6] = "sales";
    }
    /**
     * Set up players
     * @param {object} playerData - current player data used to initialize UI
     */
    PlayerController.prototype.setupPlayers = function (playerData) {
        this.playerCounter = {};
        for (var key in playerData) {
            this.createPlayerOrderToken(playerData[key]);
            this.createPlayerAgent(playerData[key]);
            this.createPlayerCubes(playerData[key]);
            this.createPlayerPanel(playerData[key]);
            this.createPlayerCounters(playerData[key]);
            this.createHandIconHoverEvents(playerData[key]);
        }
    };
    /**
     * Adjust a player's hand counter by a given amount
     *
     * @param player - player to adjust hand counter for
     * @param amount - amount to adjust hand counter by
     */
    PlayerController.prototype.adjustHand = function (player, amount) {
        this.updatePlayerCounter(player.id, "hand", amount);
    };
    /**
     * Adjust a player's idea counters by a given amount
     *
     * @param player - player to adjust idea counter for
     * @param genre - genre of idea to adjust
     * @param amount - amount to adjust idea counter by
     */
    PlayerController.prototype.adjustIdeas = function (player, genre, amount) {
        this.updatePlayerCounter(player.id, genre, amount);
    };
    /**
     * Adjust a player's money counter by a given amount
     *
     * @param player - player to adjust money counter for
     * @param amount - amount to adjust money counter by
     */
    PlayerController.prototype.adjustMoney = function (player, amount) {
        this.updatePlayerCounter(player.id, "money", amount);
    };
    /**
     * Adjust a player's income counter by a given amount
     *
     * @param player - player to adjust income counter for
     * @param amount - amount to adjust income counter by
     */
    PlayerController.prototype.adjustIncome = function (player, amount) {
        this.updatePlayerCounter(player.id, "income", amount);
    };
    /**
     * Adjust a player's point counter by a given amount
     *
     * @param player - player to adjust point counter for
     * @param amount - amount to adjust point counter by
     */
    PlayerController.prototype.adjustPoints = function (player, amount) {
        this.updatePlayerCounter(player.id, "point", amount);
    };
    /**
     * Adjust a player's ticket counter by a given amount
     *
     * @param player - player to adjust ticket counter for
     * @param amount - amount to adjust ticket counter by
     */
    PlayerController.prototype.adjustTickets = function (player, amount) {
        this.updatePlayerCounter(player.id, "ticket", amount);
    };
    /**
     * Show floating player hand when hovering over hand icon
     *
     * @param player
     */
    PlayerController.prototype.createHandIconHoverEvents = function (player) {
        var _this = this;
        dojo.connect($("aoc-player-hand-supply-" + player.id), "onmouseenter", this.ui, function () {
            if (_this.ui.player_id != player.id) {
                dojo.toggleClass("aoc-floating-hand-wrapper-" + player.id, "aoc-hidden");
            }
        });
        dojo.connect($("aoc-player-panel-hand-" + player.id + "-supply"), "onmouseenter", this.ui, function () {
            if (_this.ui.player_id != player.id) {
                dojo.toggleClass("aoc-floating-hand-wrapper-" + player.id, "aoc-hidden");
            }
        });
        dojo.connect($("aoc-player-hand-supply-" + player.id), "onmouseleave", this.ui, function () {
            if (_this.ui.player_id != player.id) {
                dojo.toggleClass("aoc-floating-hand-wrapper-" + player.id, "aoc-hidden");
            }
        });
        dojo.connect($("aoc-player-panel-hand-" + player.id + "-supply"), "onmouseleave", this.ui, function () {
            if (_this.ui.player_id != player.id) {
                dojo.toggleClass("aoc-floating-hand-wrapper-" + player.id, "aoc-hidden");
            }
        });
    };
    /**
     * Create a player order token element
     *
     * @param player - player to create token for
     */
    PlayerController.prototype.createPlayerOrderToken = function (player) {
        var playerOrderTokenDiv = '<div id="aoc-player-order-token' +
            player.id +
            '" class="aoc-player-order-token aoc-player-order-token-' +
            player.colorAsText +
            '"></div>';
        this.ui.createHtml(playerOrderTokenDiv, "aoc-player-order-space-" + player.turnOrder);
    };
    /**
     * Create a player sales agent element
     *
     * @param player - player to create sales agent for
     */
    PlayerController.prototype.createPlayerAgent = function (player) {
        var playerAgentDiv = '<div id="aoc-agent' +
            player.id +
            '" class="aoc-agent aoc-agent-' +
            player.colorAsText +
            '"></div>';
        this.ui.createHtml(playerAgentDiv, "aoc-map-agent-space-" + player.agentLocation);
    };
    /**
     * Create a player cube element
     *
     * @param player - player to create cube for
     */
    PlayerController.prototype.createPlayerCubes = function (player) {
        this.createPlayerCubeOne(player);
        this.createPlayerCubeTwo(player);
        this.createPlayerCubeThree(player);
    };
    /**
     * Create a player cube one element
     *
     * @param player - player to create cube one for
     */
    PlayerController.prototype.createPlayerCubeOne = function (player) {
        var cubeDiv = '<div id="aoc-player-cube-one-' +
            player.id +
            '" class="aoc-player-cube aoc-player-cube-' +
            player.colorAsText +
            '"></div>';
        if (player.cubeOneLocation == 0) {
            this.ui.createHtml(cubeDiv, "aoc-cube-one-space-" + player.id);
        }
        else {
            this.ui.createHtml(cubeDiv, "aoc-action-upgrade-" +
                this.actions[player.cubeOneLocation] +
                "-" +
                player.colorAsText);
        }
    };
    /**
     * Create a player cube two element
     *
     * @param player - player to create cube two for
     */
    PlayerController.prototype.createPlayerCubeTwo = function (player) {
        var cubeDiv = '<div id="aoc-player-cube-two-' +
            player.id +
            '" class="aoc-player-cube aoc-player-cube-' +
            player.colorAsText +
            '"></div>';
        if (player.cubeTwoLocation == 0) {
            this.ui.createHtml(cubeDiv, "aoc-cube-two-space-" + player.id);
        }
    };
    /**
     * Create a player cube three element
     *
     * @param player - player to create cube three for
     */
    PlayerController.prototype.createPlayerCubeThree = function (player) {
        var cubeDiv = '<div id="aoc-player-cube-three-' +
            player.id +
            '" class="aoc-player-cube aoc-player-cube-' +
            player.colorAsText +
            '"></div>';
        if (player.cubeThreeLocation == 0) {
            this.ui.createHtml(cubeDiv, "aoc-cube-three-space-" + player.id);
        }
    };
    /**
     * Create a player panel element
     *
     * @param player - player to create panel for
     */
    PlayerController.prototype.createPlayerPanel = function (player) {
        var playerPanelDiv = '<div id="aoc-player-panel-' +
            player.id +
            '" class="aoc-player-panel">' +
            '<div id="aoc-player-panel-mastery-' +
            player.id +
            '" class="aoc-player-panel-row aoc-player-panel-mastery"></div>' +
            '<div id="aoc-player-panel-ideas-1-' +
            player.id +
            '" class="aoc-player-panel-row">' +
            this.createPlayerPanelIdeaSupplyDiv(player, "crime") +
            this.createPlayerPanelIdeaSupplyDiv(player, "horror") +
            this.createPlayerPanelIdeaSupplyDiv(player, "romance") +
            "</div>" +
            '<div id="aoc-player-panel-ideas-2-' +
            player.id +
            '" class="aoc-player-panel-row">' +
            this.createPlayerPanelIdeaSupplyDiv(player, "scifi") +
            this.createPlayerPanelIdeaSupplyDiv(player, "superhero") +
            this.createPlayerPanelIdeaSupplyDiv(player, "western") +
            "</div>" +
            '<div id="aoc-player-panel-other-1' +
            player.id +
            '" class="aoc-player-panel-row">' +
            this.createPlayerPanelOtherSupplyDiv(player, "money") +
            this.createPlayerPanelOtherSupplyDiv(player, "point") +
            this.createPlayerPanelOtherSupplyDiv(player, "income") +
            "</div>" +
            '<div id="aoc-player-panel-other-2' +
            player.id +
            '" class="aoc-player-panel-row">' +
            this.createPlayerPanelOtherSupplyDiv(player, "hand") +
            this.createPlayerPanelOtherSupplyDiv(player, "tickets") +
            "</div>" +
            "</div>";
        this.ui.createHtml(playerPanelDiv, "player_board_" + player.id);
    };
    /**
     * Create a player panel idea supply element
     *
     * @param player - player to create idea supply for
     * @param genre - genre of idea supply to create
     * @returns - HTML element
     */
    PlayerController.prototype.createPlayerPanelIdeaSupplyDiv = function (player, genre) {
        var ideaSupplyDiv = '<div id="aoc-player-panel-' +
            genre +
            "-supply-" +
            player.id +
            '" class="aoc-player-panel-supply aoc-player-panel-idea-supply"><span id="aoc-player-panel-' +
            genre +
            "-count-" +
            player.id +
            '" class="aoc-player-panel-supply-count aoc-squada"></span><span id="aoc-player-panel-' +
            genre +
            "-" +
            player.id +
            '" class="aoc-idea-token aoc-idea-token-' +
            genre +
            '"></span></div>';
        return ideaSupplyDiv;
    };
    /**
     * Create a player panel other supply element (money, points, income)
     *
     * @param player - player to create other supply for
     * @param supply - other supply to create
     * @returns - HTML element
     */
    PlayerController.prototype.createPlayerPanelOtherSupplyDiv = function (player, supply) {
        var otherSupplyDiv;
        switch (supply) {
            case "hand":
                otherSupplyDiv =
                    '<div id="aoc-player-panel-hand-' +
                        player.id +
                        '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-hand-count-' +
                        player.id +
                        '" class="aoc-player-panel-supply-count aoc-squada"></span><i id="aoc-player-panel-hand-' +
                        player.id +
                        '" class="aoc-hand-icon"></i></div>';
                break;
            case "money":
                otherSupplyDiv =
                    '<div id="aoc-player-panel-money-' +
                        player.id +
                        '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-money-count-' +
                        player.id +
                        '" class="aoc-player-panel-supply-count aoc-squada"></span><i id="aoc-player-panel-money-' +
                        player.id +
                        '" class="aoc-round-token aoc-token-coin"></i></div>';
                break;
            case "point":
                otherSupplyDiv =
                    '<div id="aoc-player-panel-point-' +
                        player.id +
                        '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-point-count-' +
                        player.id +
                        '" class="aoc-player-panel-supply-count aoc-squada"></span><span id="aoc-player-panel-points-' +
                        player.id +
                        '" class="aoc-round-token aoc-token-point"></span></div>';
                break;
            case "income":
                otherSupplyDiv =
                    '<div id="aoc-player-panel-income-' +
                        player.id +
                        '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-income-count-' +
                        player.id +
                        '" class="aoc-player-panel-income-count aoc-squada"></span><i id="aoc-player-panel-income-' +
                        player.id +
                        '" class="aoc-player-panel-icon-size fa6 fa6-solid fa6-money-bill-trend-up"></i></div>';
                break;
            case "tickets":
                otherSupplyDiv =
                    '<div id="aoc-player-panel-ticket-' +
                        player.id +
                        '-supply" class="aoc-player-panel-supply aoc-player-panel-other-supply"><span id="aoc-player-panel-ticket-count-' +
                        player.id +
                        '" class="aoc-player-panel-ticket-count aoc-squada"></span><i id="aoc-player-panel-ticket-' +
                        player.id +
                        '" class="aoc-ticket-icon"></i></div>';
                break;
        }
        return otherSupplyDiv;
    };
    /**
     * Create all player counters
     *
     * @param player - player to create counters for
     */
    PlayerController.prototype.createPlayerCounters = function (player) {
        this.playerCounter[player.id] = {};
        this.createPlayerCounter(player, "crime", player.crimeIdeas);
        this.createPlayerCounter(player, "horror", player.horrorIdeas);
        this.createPlayerCounter(player, "romance", player.romanceIdeas);
        this.createPlayerCounter(player, "scifi", player.scifiIdeas);
        this.createPlayerCounter(player, "superhero", player.superheroIdeas);
        this.createPlayerCounter(player, "western", player.westernIdeas);
        this.createPlayerCounter(player, "money", player.money);
        this.createPlayerCounter(player, "point", player.score);
        this.createPlayerCounter(player, "income", player.income);
        this.createPlayerCounter(player, "hand", player.handSize);
        this.createPlayerCounter(player, "ticket", player.tickets);
    };
    /**
     * Create and initialize a player counter
     *
     * @param player - player to create counter for
     * @param counter - counter to create
     * @param initialValue - initial value of counter
     */
    PlayerController.prototype.createPlayerCounter = function (player, counter, initialValue) {
        var counterKey = counter;
        var counterPanel = "panel-" + counter;
        this.playerCounter[player.id][counterKey] = new ebg.counter();
        this.playerCounter[player.id][counterKey].create("aoc-player-" + counter + "-count-" + player.id);
        this.playerCounter[player.id][counterKey].setValue(initialValue);
        this.playerCounter[player.id][counterPanel] = new ebg.counter();
        this.playerCounter[player.id][counterPanel].create("aoc-player-" + counterPanel + "-count-" + player.id);
        this.playerCounter[player.id][counterPanel].setValue(initialValue);
    };
    PlayerController.prototype.moveUpgradeCube = function (player, cube, action) {
        var numberText = "";
        if (cube == 1) {
            numberText = "one";
        }
        if (cube == 2) {
            numberText = "two";
        }
        if (cube == 3) {
            numberText = "three";
        }
        var actionText = this.actions[action];
        var cubeDiv = "aoc-player-cube-" + numberText + "-" + player.id;
        var targetDiv = "aoc-action-upgrade-" + actionText + "-" + player.colorAsText;
        var animation = this.ui.slideToObject(cubeDiv, targetDiv);
        dojo.connect(animation, "onEnd", function () {
            dojo.removeAttr(cubeDiv, "style");
            dojo.place(cubeDiv, targetDiv);
        });
        animation.play();
    };
    /**
     * Update the value of a player counter
     *
     * @param playerId - player to update counter for
     * @param counter - counter to update
     * @param value - value to adjust counter by
     */
    PlayerController.prototype.updatePlayerCounter = function (playerId, counter, value) {
        var counterKey = counter;
        var counterPanel = "panel-" + counter;
        this.playerCounter[playerId][counterKey].incValue(value);
        this.playerCounter[playerId][counterPanel].incValue(value);
    };
    return PlayerController;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * RipoffController.ts
 *
 * Handles ripoff card logic on front-end
 *
 */
var RipoffController = /** @class */ (function () {
    function RipoffController(ui) {
        this.ui = ui;
    }
    /**
     * Set up ripoff cards
     * @param {object} ripoffCards - current ripoff card data used to initialize UI
     */
    RipoffController.prototype.setupRipoffCards = function (ripoffCards) {
        for (var key in ripoffCards) {
            this.createRipoffCard(ripoffCards[key]);
        }
    };
    /**
     * Creates a ripoff card
     * @param {object} ripoffCard - ripoff card data
     */
    RipoffController.prototype.createRipoffCard = function (ripoffCard) {
        var ripoffCardDiv = '<div id="aoc-ripoff-card-' +
            ripoffCard.id +
            '" class="aoc-ripoff-card ' +
            ripoffCard.cssClass +
            '"></div>';
        if (ripoffCard.location == globalThis.LOCATION_DECK) {
            this.ui.createHtml(ripoffCardDiv, "aoc-ripoff-deck");
        }
    };
    return RipoffController;
}());
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
var SalesOrderController = /** @class */ (function () {
    function SalesOrderController(ui) {
        this.ui = ui;
    }
    /**
     * Set up sales orders
     *
     * @param salesOrders - current sales order data used to initialize UI
     */
    SalesOrderController.prototype.setupSalesOrders = function (salesOrders) {
        for (var key in salesOrders) {
            this.createSalesOrder(salesOrders[key]);
        }
    };
    /**
     * Creates a sales order
     *
     * @param salesOrder - sales order data
     */
    SalesOrderController.prototype.createSalesOrder = function (salesOrder) {
        var salesOrderDiv = '<div id="aoc-salesorder-' +
            salesOrder.id +
            '" class="aoc-salesorder ' +
            salesOrder.cssClass +
            '"></div>';
        if (salesOrder.location == globalThis.LOCATION_MAP) {
            this.ui.createHtml(salesOrderDiv, "aoc-map-order-space-" + salesOrder.locationArg);
        }
    };
    /**
     * Flips a sales order
     *
     * @param salesOrder - sales order data
     */
    SalesOrderController.prototype.flipSalesOrder = function (salesOrder) {
        var salesOrderDiv = dojo.byId("aoc-salesorder-" + salesOrder.id);
        dojo.removeClass(salesOrderDiv, "aoc-salesorder-" + salesOrder.genre + "-facedown");
        dojo.addClass(salesOrderDiv, salesOrder.cssClass);
    };
    /**
     * Flips sales orders
     *
     * @param salesOrders - sales order data
     */
    SalesOrderController.prototype.flipSalesOrders = function (salesOrders) {
        for (var key in salesOrders) {
            this.flipSalesOrder(salesOrders[key]);
        }
    };
    return SalesOrderController;
}());
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
var TicketController = /** @class */ (function () {
    function TicketController(ui) {
        this.ui = ui;
    }
    /**
     * Set up tickets
     * @param {object} tickets - current ticket data used to initialize UI
     */
    TicketController.prototype.setupTickets = function (ticketCount) {
        for (var i = 1; i <= ticketCount; i++) {
            this.createTicket(i);
        }
    };
    /**
     * Creates a ticket
     * @param {object} ticketNum - ticket number
     */
    TicketController.prototype.createTicket = function (ticketNum) {
        var ticketDiv = '<div id="aoc-ticket-' + ticketNum + '" class="aoc-ticket"></div>';
        this.ui.createHtml(ticketDiv, "aoc-tickets-space");
    };
    TicketController.prototype.gainTicket = function (player) {
        var tickets = dojo.query(".aoc-ticket");
        if (tickets.length === 0) {
            return;
        }
        var ticket = tickets[0];
        this.ui.slideToObjectAndDestroy(ticket, "aoc-player-ticket-" + player.id, 1000);
    };
    return TicketController;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * CheckHandSize.ts
 *
 * AgeOfComics check hand size state
 *
 * State vars:
 * - game: game object reference
 * - numberToDiscard: number of cards to discard
 * - shouldUnselect: true if cards should be unselected when the number of selected cards is less than the number of cards to discard
 * - connections: map of card id to click listener
 *
 */
var CheckHandSize = /** @class */ (function () {
    function CheckHandSize(game) {
        this.game = game;
        this.numberToDiscard = 0;
        this.shouldUnselect = false;
        this.connections = {};
    }
    /**
     * Called when entering this state.
     *
     * If the current player is active, add click listeners to the cards in their hand.
     * This is done after a timeout to allow drawn cards from previous state to enter the player hand.
     *
     * stateArgs:
     *  - isCurrentPlayerActive: true if this player is the active player
     *
     * args:
     * - numberToDiscard: number of cards to discard
     *
     * @param stateArgs contains args derived from the state machine
     */
    CheckHandSize.prototype.onEnteringState = function (stateArgs) {
        var _this = this;
        if (stateArgs.isCurrentPlayerActive) {
            // Set number of cards to discard variable
            this.numberToDiscard = stateArgs.args.numberToDiscard;
            // Get all cards in hand
            var cardsInHand = dojo.byId("aoc-hand-" + stateArgs.active_player).children;
            // After a timeout, add click listeners to cards in hand
            setTimeout(function () {
                for (var i = 0; i < cardsInHand.length; i++) {
                    dojo.addClass(cardsInHand[i], "aoc-clickable");
                    _this.connections[cardsInHand[i].id] = dojo.connect(cardsInHand[i], "onclick", dojo.hitch(_this, _this.selectCard, cardsInHand[i]));
                }
            }, 1000);
        }
    };
    /**
     * Called when leaving this state.
     *
     * Make all cards unclickable and unselected.
     * Remove click listeners from cards in hand.
     */
    CheckHandSize.prototype.onLeavingState = function () {
        dojo.query(".aoc-clickable").removeClass("aoc-clickable");
        dojo.query(".aoc-selected").removeClass("aoc-selected");
        for (var key in this.connections) {
            dojo.disconnect(this.connections[key]);
        }
        this.connections = {};
    };
    /**
     * Called when the action buttons need to be updated.
     *
     * If the current player is active, add a confirmation button that starts disabled.
     *
     * stateArgs:
     *  - isCurrentPlayerActive: true if this player is the active player
     *
     * @param stateArgs contains args derived from the state machine
     */
    CheckHandSize.prototype.onUpdateActionButtons = function (stateArgs) {
        var _this = this;
        if (stateArgs.isCurrentPlayerActive) {
            // Add confirmation button
            gameui.addActionButton("aoc-confirm-discard", _("Confirm"), function () {
                _this.confirmDiscard();
            });
            // Disable confirmation button + add custom styling
            dojo.addClass("aoc-confirm-discard", "aoc-button-disabled");
            dojo.addClass("aoc-confirm-discard", "aoc-button");
        }
    };
    /**
     * Called when the confirmation button is clicked.
     *
     * Send the selected cards to the server.
     */
    CheckHandSize.prototype.confirmDiscard = function () {
        // Initialize string to store card ids in comma separated list
        var cardsToDiscard = "";
        // Get all cards with the `selected` class
        var selectedCards = dojo.query(".aoc-selected");
        // For each card, add its id to the string
        for (var i = 0; i < selectedCards.length; i++) {
            var card = selectedCards[i];
            if (i == 0) {
                cardsToDiscard += card.id.split("-")[2];
            }
            else {
                cardsToDiscard += "," + card.id.split("-")[2];
            }
        }
        // Send the card ids to the server
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_CONFIRM_DISCARD, {
            cardsToDiscard: cardsToDiscard,
        });
    };
    /**
     * Called when a card is clicked.
     *
     * If the card is clickable, toggle its selected status.
     *
     * @param card the card that was clicked
     */
    CheckHandSize.prototype.selectCard = function (card) {
        // Toggle clicabile and selected status
        dojo.toggleClass(card, "aoc-clickable");
        dojo.toggleClass(card, "aoc-selected");
        // Update confirmation button status
        this.updateConfirmationButtonStatus();
    };
    /**
     * Called when the number of selected cards is equal to the number of cards to discard.
     *
     * Make all unselected cards unclickable.
     * Disconnect click listeners from unselected cards.
     *
     * This is done to prevent the player from selecting more cards than they are able to discard.
     */
    CheckHandSize.prototype.toggleCardStatus = function () {
        // Get all unselected cards
        var unselectedCards = dojo.query(".aoc-clickable");
        // For each card, make it unclickable and disconnect its click listener
        for (var i = 0; i < unselectedCards.length; i++) {
            var unselectedCard = unselectedCards[i];
            dojo.toggleClass(unselectedCard, "aoc-clickable");
            dojo.disconnect(this.connections[unselectedCard.id]);
        }
        // Set shouldUnselect to true to indicate that cards should be unselected when the number
        // of selected cards is less than the number of cards to discard
        this.shouldUnselect = true;
    };
    /**
     * Called when the number of selected cards is less than the number of cards to discard.
     *
     * Make all unselected cards clickable.
     * Add click listeners to unselected cards.
     */
    CheckHandSize.prototype.untoggleCardStatus = function () {
        // Get all unselected cards - aka all cards that are not selected and not clickable
        var cardsToUntoggle = dojo.query("div#aoc-hand-" +
            this.game.player_id +
            "> .aoc-card:not(.aoc-selected):not(.aoc-clickable)");
        // For each card, make it clickable and add a click listener
        for (var i = 0; i < cardsToUntoggle.length; i++) {
            var card = cardsToUntoggle[i];
            dojo.toggleClass(card, "aoc-clickable");
            this.connections[card.id] = dojo.connect(card, "onclick", dojo.hitch(this, this.selectCard, card));
        }
        // Set shouldUnselect to false to indicate that cards should not be unselected when the number
        // of selected cards is less than the number of cards to discard
        this.shouldUnselect = false;
    };
    /**
     * Called when the number of selected cards changes.
     *
     * If the number of selected cards is equal to the number of cards to discard, make all unselected cards unclickable.
     * If the number of selected cards is less than the number of cards to discard, make all unselected cards clickable.
     */
    CheckHandSize.prototype.updateConfirmationButtonStatus = function () {
        // Get all selected cards
        var selectedCards = dojo.query(".aoc-selected");
        // If the number of selected cards is equal to the number of cards to discard,
        // make all unselected cards unclickable, then enable the confirmation button
        if (selectedCards.length == this.numberToDiscard) {
            this.toggleCardStatus();
            dojo.removeClass("aoc-confirm-discard", "aoc-button-disabled");
        }
        else {
            // If the number of selected cards is less than the number of cards to discard disable the confirmation button
            dojo.addClass("aoc-confirm-discard", "aoc-button-disabled");
            // If shouldUnselect is true, make all unselected cards clickable
            if (this.shouldUnselect) {
                this.untoggleCardStatus();
            }
        }
    };
    return CheckHandSize;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * CompleteSetup.ts
 *
 * AgeOfComics complete setup state
 *
 * State vars:
 * - game: game object reference
 *
 */
var CompleteSetup = /** @class */ (function () {
    function CompleteSetup(game) {
        this.game = game;
    }
    /**
     * Called when entering this state.
     * Upon entering this state, the game is ready to play.
     *
     * stateArgs:
     *  - isCurrentPlayerActive: true if this player is the active player
     *
     * @param stateArgs contains args derived from the state machine
     */
    CompleteSetup.prototype.onEnteringState = function (stateArgs) {
        // Make the card market visible and adapt the viewport size
        dojo.toggleClass("aoc-card-market", "aoc-hidden", false);
        // Adapt the viewport size
        this.game.adaptViewportSize();
    };
    CompleteSetup.prototype.onLeavingState = function () { };
    CompleteSetup.prototype.onUpdateActionButtons = function (stateArgs) { };
    return CompleteSetup;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * GameEnd.ts
 *
 * AgeOfComics game end state
 *
 * State vars:
 * - game: game object reference
 *
 */
var GameEnd = /** @class */ (function () {
    function GameEnd(game) {
        this.game = game;
    }
    GameEnd.prototype.onEnteringState = function (stateArgs) { };
    GameEnd.prototype.onLeavingState = function () { };
    GameEnd.prototype.onUpdateActionButtons = function (stateArgs) { };
    return GameEnd;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * GameSetup.ts
 *
 * AgeOfComics game setup state
 *
 * State vars:
 * - game: game object reference
 *
 */
var GameSetup = /** @class */ (function () {
    function GameSetup(game) {
        this.game = game;
    }
    GameSetup.prototype.onEnteringState = function (stateArgs) { };
    GameSetup.prototype.onLeavingState = function () { };
    GameSetup.prototype.onUpdateActionButtons = function (stateArgs) { };
    return GameSetup;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * NextPlayer.ts
 *
 * AgeOfComics next player state
 *
 * State vars:
 * - game: game object reference
 *
 */
var NextPlayer = /** @class */ (function () {
    function NextPlayer(game) {
        this.game = game;
    }
    NextPlayer.prototype.onEnteringState = function (stateArgs) { };
    NextPlayer.prototype.onLeavingState = function () { };
    NextPlayer.prototype.onUpdateActionButtons = function (stateArgs) { };
    return NextPlayer;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * NextPlayerSetup.ts
 *
 * AgeOfComics next player setup state
 *
 * State vars:
 * - game: game object reference
 *
 */
var NextPlayerSetup = /** @class */ (function () {
    function NextPlayerSetup(game) {
        this.game = game;
    }
    NextPlayerSetup.prototype.onEnteringState = function (stateArgs) { };
    NextPlayerSetup.prototype.onLeavingState = function () { };
    NextPlayerSetup.prototype.onUpdateActionButtons = function (stateArgs) { };
    return NextPlayerSetup;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformDevelop.ts
 *
 * AgeOfComics perform develop state
 *
 * State vars:
 * - game: game object reference
 * - connections: object containing dojo connections
 *
 */
var PerformDevelop = /** @class */ (function () {
    function PerformDevelop(game) {
        this.game = game;
        this.connections = {};
    }
    /**
     * Called when entering this state
     *
     * Creates possible develop actions
     *
     * stateArgs:
     * - isCurrentPlayerActive: true if this player is the active player
     *
     * args:
     * - availableGenres: the genres that the player can develop from the deck (aka those that have at least one card in the deck or discard)
     * - canDevelopFromDeck: true if the player can develop from the deck
     *
     * @param stateArgs
     */
    PerformDevelop.prototype.onEnteringState = function (stateArgs) {
        if (stateArgs.isCurrentPlayerActive) {
            this.createDevelopActions();
            if (stateArgs.args.canDevelopFromDeck) {
                this.createDevelopFromDeckActions(stateArgs.args.availableGenres);
            }
        }
    };
    /**
     * Called when leaving this state
     *
     * Removes click listeners from cards
     */
    PerformDevelop.prototype.onLeavingState = function () {
        // Remove click listeners from cards
        dojo.query(".aoc-clickable").removeClass("aoc-clickable");
        // Remove all click listeners
        for (var key in this.connections) {
            dojo.disconnect(this.connections[key]);
        }
        // Clear connections object
        this.connections = {};
        // Remove develop from deck buttons
        var buttonRowDiv = dojo.byId("aoc-develop-from-deck-buttons");
        if (buttonRowDiv) {
            buttonRowDiv.remove();
        }
    };
    PerformDevelop.prototype.onUpdateActionButtons = function (stateArgs) { };
    /**
     * Creates develop actions for the player
     */
    PerformDevelop.prototype.createDevelopActions = function () {
        // Make the top card of the comic deck clickable and add a click listener
        var topCardOfDeck = dojo.byId("aoc-comic-deck").lastChild;
        topCardOfDeck.classList.add("aoc-clickable");
        var topCardOfDeckId = topCardOfDeck.id.split("-")[2];
        this.connections["comic" + topCardOfDeckId] = dojo.connect(dojo.byId(topCardOfDeck.id), "onclick", dojo.hitch(this, this.developComic, topCardOfDeckId, true));
        // Get all cards in comic market
        var cardElements = dojo.byId("aoc-comics-available").children;
        // Make all cards in comic market clickable and add click listeners
        for (var key in cardElements) {
            var card = cardElements[key];
            if (card.id) {
                card.classList.add("aoc-clickable");
                var cardId = card.id.split("-")[2];
                this.connections["comic" + cardId] = dojo.connect(dojo.byId(card.id), "onclick", dojo.hitch(this, this.developComic, cardId, false));
            }
        }
    };
    /**
     * Creates develop from deck actions for the player
     *
     * @param availableGenres the genres that the player can develop from the deck (aka those that have at least one card in the deck or discard)
     */
    PerformDevelop.prototype.createDevelopFromDeckActions = function (availableGenres) {
        // Create div for develop from deck buttons
        var buttonRowDiv = "<div id='aoc-develop-from-deck-buttons' class='aoc-action-panel-row'><div id='aoc-seach-icon' class='aoc-search-icon'></div></div>";
        this.game.createHtml(buttonRowDiv, "page-title");
        // Get all genres
        var genres = this.game.getGenres();
        // For each genre, create a button
        for (var key in genres) {
            var genre = genres[key];
            var buttonDiv = "<div id='aoc-develop-from-deck-" +
                genre +
                "' class='aoc-mini-comic-card aoc-mini-comic-card-" +
                genre +
                "'></div>";
            this.game.createHtml(buttonDiv, "aoc-develop-from-deck-buttons");
            // If the player can develop from the deck, make the button clickable and add a click listener
            if (availableGenres[genre] > 0) {
                dojo.addClass("aoc-develop-from-deck-" + genre, "aoc-image-clickable");
                this.connections["developFromDeck" + genre] = dojo.connect(dojo.byId("aoc-develop-from-deck-" + genre), "onclick", dojo.hitch(this, this.developComicFromDeck, genre));
            }
            else {
                // If the player cannot develop from the deck, make the button disabled
                dojo.addClass("aoc-develop-from-deck-" + genre, "aoc-image-disabled");
            }
        }
    };
    /**
     * Called when a player clicks on a comic card
     *
     * @param comicId the id of the comic card
     * @param topOfDeck true if the card is the top card of the deck
     */
    PerformDevelop.prototype.developComic = function (comicId, topOfDeck) {
        // Call the develop comic action for the comic
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_DEVELOP_COMIC, {
            comicId: comicId,
            topOfDeck: topOfDeck,
        });
    };
    /**
     * Called when a player clicks on a develop from deck button
     *
     * @param genre the genre of the comic to develop
     */
    PerformDevelop.prototype.developComicFromDeck = function (genre) {
        // Call the develop from deck action for the genre
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_DEVELOP_FROM_GENRE, {
            genre: genre,
        });
    };
    return PerformDevelop;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformHire.ts
 *
 * AgeOfComics perform hire state
 *
 * State vars:
 * - game: game object reference
 * - connections: object containing dojo connections
 *
 */
var PerformHire = /** @class */ (function () {
    function PerformHire(game) {
        this.game = game;
        this.connections = {};
    }
    /**
     * Called when entering this state
     * Creates possible hire actions
     *
     * stateArgs:
     * - isCurrentPlayerActive: true if this player is the active player
     *
     * args:
     * - canHireArtist: true if the player can hire an artist
     * - canHireWriter: true if the player can hire a writer
     *
     * @param stateArgs
     */
    PerformHire.prototype.onEnteringState = function (stateArgs) {
        if (stateArgs.isCurrentPlayerActive) {
            if (stateArgs.args.canHireArtist == 1) {
                this.createHireActions("artist");
            }
            if (stateArgs.args.canHireWriter == 1) {
                this.createHireActions("writer");
            }
        }
    };
    /**
     * Called when leaving this state
     * Removes click listeners from cards
     */
    PerformHire.prototype.onLeavingState = function () {
        dojo.query(".aoc-clickable").removeClass("aoc-clickable");
        for (var key in this.connections) {
            dojo.disconnect(this.connections[key]);
        }
        this.connections = {};
    };
    PerformHire.prototype.onUpdateActionButtons = function (stateArgs) { };
    /**
     * Creates possible hire actions
     *
     * @param creativeType - the type of creative to hire
     */
    PerformHire.prototype.createHireActions = function (creativeType) {
        // Make top card of creative deck clickable and add click listener
        var topCardOfDeck = dojo.byId("aoc-" + creativeType + "-deck").lastChild;
        topCardOfDeck.classList.add("aoc-clickable");
        var topCardOfDeckId = topCardOfDeck.id.split("-")[2];
        this.connections[creativeType + topCardOfDeckId] = dojo.connect(dojo.byId(topCardOfDeck.id), "onclick", dojo.hitch(this, this.hireCreative, topCardOfDeckId, creativeType));
        // Get all cards of the specified creative market
        var cardElements = dojo.byId("aoc-" + creativeType + "s-available").children;
        // Make all cards in creative market clickable and add click listeners
        for (var key in cardElements) {
            var card = cardElements[key];
            if (card.id) {
                card.classList.add("aoc-clickable");
                var cardId = card.id.split("-")[2];
                this.connections[creativeType + cardId] = dojo.connect(dojo.byId(card.id), "onclick", dojo.hitch(this, this.hireCreative, cardId, creativeType));
            }
        }
    };
    /**
     * Hires a creative
     *
     * @param cardId - the id of the card to hire
     * @param creativeType - the type of creative to hire
     */
    PerformHire.prototype.hireCreative = function (cardId, creativeType) {
        // Call the hire creative action
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_HIRE_CREATIVE, {
            cardId: cardId,
            creativeType: creativeType,
        });
    };
    return PerformHire;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformIdeas.ts
 *
 * AgeOfComics perform ideas state
 *
 * State vars:
 * - game: game object reference
 * - shouldUnselect: true if ideas should be unselected when the number of selected ideas is less than the number of ideas to gain
 * - ideasFromBoard: number of ideas to gain from the board
 * - connections: map of idea id to click listener
 *
 */
var PerformIdeas = /** @class */ (function () {
    function PerformIdeas(game) {
        this.game = game;
        this.shouldUnselect = false;
        this.ideasFromBoard = 0;
        this.connections = {};
    }
    /**
     * Called when entering this state.
     * Creates possible idea actions.
     *
     * stateArgs:
     * - isCurrentPlayerActive: true if this player is the active player
     *
     * args:
     * - ideasFromBoard: number of ideas to gain from the board
     *
     * @param stateArgs
     */
    PerformIdeas.prototype.onEnteringState = function (stateArgs) {
        if (stateArgs.isCurrentPlayerActive) {
            var ideasFromBoard = stateArgs.args.ideasFromBoard;
            this.ideasFromBoard = ideasFromBoard;
            this.createIdeaTokensFromSupplyActions();
            this.createIdeaTokensOnBoardActions(ideasFromBoard);
        }
    };
    /**
     * Called when leaving this state.
     * Removes click listeners from cards.
     * Removes idea selection div.
     */
    PerformIdeas.prototype.onLeavingState = function () {
        dojo.query(".aoc-clickable").removeClass("aoc-clickable");
        dojo.query(".aoc-selected").removeClass("aoc-selected");
        dojo.disconnect(this.connections["aoc-idea-token-crime"]);
        dojo.disconnect(this.connections["aoc-idea-token-horror"]);
        dojo.disconnect(this.connections["aoc-idea-token-romance"]);
        dojo.disconnect(this.connections["aoc-idea-token-scifi"]);
        dojo.disconnect(this.connections["aoc-idea-token-superhero"]);
        dojo.disconnect(this.connections["aoc-idea-token-western"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-crime"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-horror"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-romance"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-scifi"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-superhero"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-western"]);
        dojo.disconnect(this.connections["aoc-idea-cancel-1"]);
        dojo.disconnect(this.connections["aoc-idea-cancel-2"]);
        this.connections = {};
        var selectionDiv = dojo.byId("aoc-idea-token-selection");
        if (selectionDiv) {
            selectionDiv.remove();
        }
    };
    /**
     * Called when entering state to update buttons.
     *
     * stateArgs:
     * - isCurrentPlayerActive: true if this player is the active player
     *
     * Add a confirmation button that starts disabled.
     *
     * @param stateArgs
     */
    PerformIdeas.prototype.onUpdateActionButtons = function (stateArgs) {
        var _this = this;
        if (stateArgs.isCurrentPlayerActive) {
            gameui.addActionButton("aoc-confirm-gain-ideas", _("Confirm"), function () {
                _this.confirmGainIdeas();
            });
            dojo.addClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
            dojo.addClass("aoc-confirm-gain-ideas", "aoc-button");
        }
    };
    /**
     * Called when the player confirms their idea selection.
     * Sends the selected ideas to the server.
     *
     */
    PerformIdeas.prototype.confirmGainIdeas = function () {
        // Get all selected ideas from supply
        var selectedIdeasFromSupply = dojo.query(".aoc-supply-idea-selection");
        // Initialize string to store idea ids in comma separated list
        var selectedIdeasFromSupplyGenres = "";
        // For each idea, add its id to the string
        for (var i = 0; i < selectedIdeasFromSupply.length; i++) {
            var idea = selectedIdeasFromSupply[i];
            if (i == 0) {
                selectedIdeasFromSupplyGenres += this.game.getGenreId(idea.id.split("-")[3]);
            }
            else {
                selectedIdeasFromSupplyGenres +=
                    "," + this.game.getGenreId(idea.id.split("-")[3]);
            }
        }
        // Get all selected ideas from board
        var selectedIdeasFromBoard = dojo.query(".aoc-selected");
        // Initialize string to store idea ids in comma separated list
        var selectedIdeasFromBoardGenres = "";
        // For each idea, add its id to the string
        for (var i = 0; i < selectedIdeasFromBoard.length; i++) {
            var idea = selectedIdeasFromBoard[i];
            if (i == 0) {
                selectedIdeasFromBoardGenres += this.game.getGenreId(idea.id.split("-")[3]);
            }
            else {
                selectedIdeasFromBoardGenres +=
                    "," + this.game.getGenreId(idea.id.split("-")[3]);
            }
        }
        // Send the idea ids to the server
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_CONFIRM_GAIN_IDEAS, {
            ideasFromBoard: selectedIdeasFromBoardGenres,
            ideasFromSupply: selectedIdeasFromSupplyGenres,
        });
    };
    /**
     * Creates a div to hold an idea selection.
     *
     * @param idNum the id number of the div
     */
    PerformIdeas.prototype.createIdeaSelectionDiv = function (idNum) {
        // Create div for idea selection
        var ideaSelectionDiv = '<div id="aoc-supply-idea-selection-container-' +
            idNum +
            '" class="aoc-selection-container"><i id="aoc-idea-cancel-' +
            idNum +
            '" class="fa fa-lg fa-times-circle aoc-start-idea-remove aoc-hidden"></i></div>';
        // Add div to page
        this.game.createHtml(ideaSelectionDiv, "aoc-select-supply-idea-containers");
        // Add click listener to cancel button
        this.connections["aoc-idea-cancel-" + idNum] = dojo.connect(dojo.byId("aoc-idea-cancel-" + idNum), "onclick", dojo.hitch(this, "removeIdea", idNum));
    };
    /**
     * Creates possible idea actions from supply.
     */
    PerformIdeas.prototype.createIdeaTokensFromSupplyActions = function () {
        // Create div for idea token selection
        var ideaTokenSelectionDiv = "<div id='aoc-idea-token-selection' class='aoc-action-panel-row'></div>";
        this.game.createHtml(ideaTokenSelectionDiv, "page-title");
        // Get all genres
        var genres = this.game.getGenres();
        // For each genre, create a button
        for (var key in genres) {
            var genre = genres[key];
            var ideaTokenDiv = "<div id='aoc-select-supply-idea-token-" +
                genre +
                "' class='aoc-idea-token aoc-idea-token-" +
                genre +
                "'></div>";
            this.game.createHtml(ideaTokenDiv, "aoc-idea-token-selection");
            this.connections["aoc-select-supply-idea-token-" + genre] = dojo.connect(dojo.byId("aoc-select-supply-idea-token-" + genre), "onclick", dojo.hitch(this, "selectIdeaFromSupply", genre));
        }
        // Create divs for idea selection containers
        var selectionBoxesDiv = "<div id='aoc-select-supply-idea-containers' class='aoc-select-containers'></div>";
        this.game.createHtml(selectionBoxesDiv, "aoc-idea-token-selection");
        this.createIdeaSelectionDiv(1);
        this.createIdeaSelectionDiv(2);
    };
    /**
     * Creates possible idea actions from board.
     *
     * @param ideasFromBoard the number of ideas to gain from the board
     */
    PerformIdeas.prototype.createIdeaTokensOnBoardActions = function (ideasFromBoard) {
        // If there are ideas to gain from the board, make all idea spaces clickable and add click listeners
        if (ideasFromBoard > 0) {
            // Get all idea spaces
            var ideaSpaces = dojo.byId("aoc-action-ideas-idea-spaces").children;
            // For each idea space, make it clickable and add a click listener
            for (var key in ideaSpaces) {
                var ideaSpace = ideaSpaces[key];
                if (dojo.hasClass(ideaSpace, "aoc-action-ideas-idea-space") &&
                    ideaSpace.children.length > 0) {
                    var ideaTokenDiv = ideaSpace.children[0];
                    dojo.addClass(ideaTokenDiv, "aoc-clickable");
                    this.connections[ideaTokenDiv.id] = dojo.connect(dojo.byId(ideaTokenDiv.id), "onclick", dojo.hitch(this, "selectIdeaFromBoard", ideaTokenDiv.id, ideasFromBoard));
                }
            }
        }
    };
    /**
     * Gets the first empty idea selection div.
     *
     * @returns the first empty idea selection div or null if there are no empty idea selection divs
     */
    PerformIdeas.prototype.getFirstEmptyIdeaSelectionDiv = function () {
        // Get all idea selection divs
        var allDivs = dojo.query(".aoc-selection-container");
        // For each idea selection div, if it has no children, return it
        for (var i = 0; i < allDivs.length; i++) {
            var div = allDivs[i];
            if (div.children.length == 1) {
                return div;
            }
        }
        // If there are no empty idea selection divs, return null
        return null;
    };
    /**
     * Removes an idea from the idea selection div.
     *
     * @param slotId the id of the idea selection div
     */
    PerformIdeas.prototype.removeIdea = function (slotId) {
        // Remove idea from selection div
        var ideaDiv = dojo.byId("aoc-selected-idea-box-" + slotId);
        ideaDiv.remove();
        // Hide cancel button
        dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", true);
        // Set status of confirmation button
        this.setButtonConfirmationStatus();
    };
    /**
     * Called when a player clicks on an idea on the board.
     *
     * @param divId the id of the idea div
     * @param ideasFromBoard the number of ideas to gain from the board
     */
    PerformIdeas.prototype.selectIdeaFromBoard = function (divId, ideasFromBoard) {
        // Toggle selected and clickable status
        dojo.byId(divId).classList.toggle("aoc-selected");
        dojo.byId(divId).classList.toggle("aoc-clickable");
        // Get all selected ideas
        var selectedIdeas = dojo.query(".aoc-selected");
        // If the number of selected ideas is equal to the number of ideas to gain from the board,
        // make all unselected ideas unclickable and disconnect their click listeners
        if (selectedIdeas.length == ideasFromBoard) {
            var unselectedIdeas = dojo.query(".aoc-clickable");
            for (var i = 0; i < unselectedIdeas.length; i++) {
                var unselectedIdea = unselectedIdeas[i];
                dojo.byId(unselectedIdea.id).classList.toggle("aoc-clickable");
                dojo.disconnect(this.connections[unselectedIdea.id]);
            }
            // Set shouldUnselect to true to indicate that ideas should be unselected when the number
            // of selected ideas is less than the number of ideas to gain from the board
            this.shouldUnselect = true;
        }
        // If the number of selected ideas is less than the number of ideas to gain from the board
        // and shouldUnselect is true, make all unselected ideas clickable and add click listeners
        else if (selectedIdeas.length < ideasFromBoard && this.shouldUnselect) {
            var ideasToActivate = dojo.query(".aoc-action-ideas-idea-space > .aoc-idea-token:not(.aoc-selected):not(.aoc-clickable)");
            for (var i = 0; i < ideasToActivate.length; i++) {
                var ideaToActivate = ideasToActivate[i];
                dojo.byId(ideaToActivate.id).classList.toggle("aoc-clickable");
                this.connections[ideaToActivate.id] = dojo.connect(dojo.byId(ideaToActivate.id), "onclick", dojo.hitch(this, "selectIdeaFromBoard", ideaToActivate.id, ideasFromBoard));
            }
            // Set shouldUnselect to false to indicate that ideas should not be unselected when the number
            // of selected ideas is less than the number of ideas to gain from the board
            this.shouldUnselect = false;
        }
        // Set status of confirmation button
        this.setButtonConfirmationStatus();
    };
    /**
     * Called when a player clicks on an idea on the supply.
     *
     * @param genre the genre of the idea
     */
    PerformIdeas.prototype.selectIdeaFromSupply = function (genre) {
        // Get first empty idea selection div
        var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();
        // If there are no empty idea selection divs, return
        if (firstEmptySelectionDiv == null) {
            return;
        }
        // Get id of idea selection div
        var slotId = firstEmptySelectionDiv.id.split("-")[5];
        // Create div for selected idea
        var tokenDiv = '<div id="aoc-selected-idea-box-' +
            slotId +
            '"><div id="aoc-selected-idea-' +
            genre +
            '" class="aoc-supply-idea-selection aoc-idea-token aoc-idea-token-' +
            genre +
            '"></div></div>';
        // Add div to the idea selection div
        this.game.createHtml(tokenDiv, firstEmptySelectionDiv.id);
        // Unhide cancel button
        dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", false);
        // Set status of confirmation button
        this.setButtonConfirmationStatus();
    };
    /**
     * Sets the status of the confirmation button.
     * If all idea selection divs are full and all possible ideas from board are selected, enable the confirmation button.
     * If either are not true, disable the confirmation button.
     */
    PerformIdeas.prototype.setButtonConfirmationStatus = function () {
        var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();
        var selectedIdeasFromBoard = dojo.query(".aoc-selected");
        if (firstEmptySelectionDiv == null &&
            selectedIdeasFromBoard.length == this.ideasFromBoard) {
            dojo.addClass("aoc-confirm-gain-ideas", "aoc-button");
            dojo.removeClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
        }
        else {
            dojo.addClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
            dojo.removeClass("aoc-confirm-gain-ideas", "aoc-button");
        }
    };
    return PerformIdeas;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformPrint.ts
 *
 * Age of Comics perform print state
 *
 * State vars:
 *  game: game object reference
 *  connections: click listener map
 *
 */
var PerformPrint = /** @class */ (function () {
    function PerformPrint(game) {
        this.game = game;
        this.connections = {};
    }
    /**
     * Called when entering this state
     * Creates the print menu and events
     *
     * stateArgs:
     *  - isCurrentPlayerActive: true if this player is the active player
     *
     * args:
     * - printableComics: comics the player can print
     * - artists: artists the player can use
     * - writers: writers the player can use
     *
     * @param stateArgs
     */
    PerformPrint.prototype.onEnteringState = function (stateArgs) {
        if (stateArgs.isCurrentPlayerActive) {
            dojo.toggleClass("aoc-print-menu", "aoc-hidden");
            this.createCards(stateArgs.args.printableComics, "comic");
            this.createCards(stateArgs.args.artists, "artist");
            this.createCards(stateArgs.args.writers, "writer");
        }
    };
    /**
     * Called when leaving this state
     * Removes the print menu and events
     *
     */
    PerformPrint.prototype.onLeavingState = function () {
        // Hide the print menu
        dojo.toggleClass("aoc-print-menu", "aoc-hidden", true);
        // Remove the css classes from the comics
        dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
        dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");
        // Remove the listeners
        for (var connection in this.connections) {
            dojo.disconnect(this.connections[connection]);
        }
        // Clear the menu
        dojo.empty("aoc-print-comics-menu");
        dojo.empty("aoc-print-artists-menu");
        dojo.empty("aoc-print-writers-menu");
    };
    /**
     * Called when game enters the state. Creates confirmation button for the state
     *
     * stateArgs:
     *  - isCurrentPlayerActive: true if this player is the active player
     *
     * args:
     * - printableComics: comics the player can print
     * - artists: artists the player can use
     * - writers: writers the player can use
     *
     * @param stateArgs
     */
    PerformPrint.prototype.onUpdateActionButtons = function (stateArgs) {
        var _this = this;
        if (stateArgs.isCurrentPlayerActive) {
            gameui.addActionButton("aoc-confirm-print", _("Confirm"), function () {
                _this.confirmPrint();
            });
            dojo.addClass("aoc-confirm-print", "aoc-button-disabled");
            dojo.addClass("aoc-confirm-print", "aoc-button");
            if (stateArgs.args.selectedActionSpace == "0") {
                gameui.addActionButton("aoc-skip-double-print", _("Skip"), function () {
                    _this.skipDoublePrint();
                });
                dojo.addClass("aoc-skip-double-print", "aoc-button");
            }
        }
    };
    /**
     * Called when the confirm button is clicked
     * Sends the selected cards to the server
     */
    PerformPrint.prototype.confirmPrint = function () {
        // Get the selected cards
        var selectedComic = dojo.query("#aoc-print-comics-menu > .aoc-card-selected")[0];
        var selectedArtist = dojo.query("#aoc-print-artists-menu > .aoc-card-selected")[0];
        var selectedWriter = dojo.query("#aoc-print-writers-menu > .aoc-card-selected")[0];
        // Get the ids of the selected cards
        var selectedComicId = selectedComic.id.split("-")[4];
        var selectedArtistId = selectedArtist.id.split("-")[4];
        var selectedWriterId = selectedWriter.id.split("-")[4];
        // Send the selected cards to the server
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_PRINT_COMIC, {
            comicId: selectedComicId,
            artistId: selectedArtistId,
            writerId: selectedWriterId,
        });
        this.onLeavingState();
    };
    /**
     * Create cards for the print menu
     *
     * @param cards Array of cards to create
     * @param cardType Type of card to create
     */
    PerformPrint.prototype.createCards = function (cards, cardType) {
        var _this = this;
        var _loop_1 = function (card) {
            var cardTypeClass = void 0;
            switch (card.type) {
                case "comic":
                    cardTypeClass = "aoc-comic-card";
                    break;
                case "ripoff":
                    cardTypeClass = "aoc-ripoff-card";
                    break;
                case "artist":
                    cardTypeClass = "aoc-creative-card";
                    break;
                case "writer":
                    cardTypeClass = "aoc-creative-card";
                    break;
            }
            var cardDiv = "<div id='aoc-print-menu-" +
                cardType +
                "-" +
                card.id +
                "' class='aoc-card " +
                cardTypeClass +
                " " +
                card.baseClass +
                "'></div>";
            // Add the div to the menu
            this_1.game.createHtml(cardDiv, "aoc-print-" + cardType + "s-menu");
            // Add the card's listener
            this_1.connections[card.id] = dojo.connect(dojo.byId("aoc-print-menu-" + cardType + "-" + card.id), "onclick", this_1, function () {
                _this.handleCardSelection(card.id, cardType);
            });
        };
        var this_1 = this;
        // Create a div for each card
        for (var _i = 0, cards_1 = cards; _i < cards_1.length; _i++) {
            var card = cards_1[_i];
            _loop_1(card);
        }
    };
    /**
     * Handle card selection
     *
     * @param cardId Id of the card that was selected
     * @param cardType Type of card that was selected
     */
    PerformPrint.prototype.handleCardSelection = function (cardId, cardType) {
        var _this = this;
        // Remove the selected and unselected classes from all cards of type
        dojo
            .query("#aoc-print-" + cardType + "s-menu > .aoc-card-selected")
            .removeClass("aoc-card-selected");
        dojo
            .query("#aoc-print-" + cardType + "s-menu > .aoc-card-unselected")
            .removeClass("aoc-card-unselected");
        var selectedCardDiv = dojo.byId("aoc-print-menu-" + cardType + "-" + cardId);
        // Add the selected class to the selected card
        dojo.toggleClass(selectedCardDiv, "aoc-card-selected");
        // Disconnect + delete the card's listener
        dojo.disconnect(this.connections[cardId]);
        delete this.connections[cardId];
        // Get all cards of type
        var allCards = dojo.byId("aoc-print-" + cardType + "s-menu").children;
        var _loop_2 = function (i) {
            var card = allCards[i];
            // Check if the card is not the selected card
            if (card.id != selectedCardDiv.id) {
                var cardDivId_1 = card.id.split("-")[4];
                // Add the unselected class to the comic
                dojo.toggleClass(card.id, "aoc-card-unselected", true);
                //If the comic doesn't have a listener, add one
                if (!this_2.connections[cardDivId_1]) {
                    this_2.connections[cardDivId_1] = dojo.connect(dojo.byId(card.id), "onclick", this_2, function () {
                        _this.handleCardSelection(cardDivId_1, cardType);
                    });
                }
            }
        };
        var this_2 = this;
        for (var i = 0; i < allCards.length; i++) {
            _loop_2(i);
        }
        // Set confirm button status
        this.setButtonConfirmationStatus();
    };
    PerformPrint.prototype.skipDoublePrint = function () {
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_SKIP_DOUBLE_PRINT, {});
        this.onLeavingState();
    };
    /**
     * Sets the status of the confirm button
     *
     * If 3 cards are selected, the confirm button is enabled
     */
    PerformPrint.prototype.setButtonConfirmationStatus = function () {
        var selectedCards = dojo.query(".aoc-card-selected");
        if (selectedCards.length == 3) {
            dojo.removeClass("aoc-confirm-print", "aoc-button-disabled");
        }
        else {
            dojo.addClass("aoc-confirm-print", "aoc-button-disabled");
        }
    };
    return PerformPrint;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformPrintBonus.ts
 *
 * Age of Comics perform print bonus state
 *
 * State vars:
 *  game: game object reference
 *  connections: click listener map
 *
 */
var PerformPrintBonus = /** @class */ (function () {
    function PerformPrintBonus(game) {
        this.game = game;
        this.connections = {};
    }
    PerformPrintBonus.prototype.onEnteringState = function (stateArgs) {
        if (stateArgs.isCurrentPlayerActive) {
            this.createIdeaTokensFromSupplyActions();
        }
    };
    PerformPrintBonus.prototype.onLeavingState = function () {
        dojo.query(".aoc-clickable").removeClass("aoc-clickable");
        dojo.query(".aoc-selected").removeClass("aoc-selected");
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-crime"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-horror"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-romance"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-scifi"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-superhero"]);
        dojo.disconnect(this.connections["aoc-select-supply-idea-token-western"]);
        dojo.disconnect(this.connections["aoc-idea-cancel-1"]);
        dojo.disconnect(this.connections["aoc-idea-cancel-2"]);
        this.connections = {};
        var selectionDiv = dojo.byId("aoc-idea-token-selection");
        if (selectionDiv) {
            selectionDiv.remove();
        }
    };
    PerformPrintBonus.prototype.onUpdateActionButtons = function (stateArgs) {
        var _this = this;
        gameui.addActionButton("aoc-confirm-gain-ideas", _("Confirm"), function () {
            _this.confirmGainIdeas();
        });
        dojo.addClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
        dojo.addClass("aoc-confirm-gain-ideas", "aoc-button");
    };
    /**
     * Called when the player confirms their idea selection.
     * Sends the selected ideas to the server.
     *
     */
    PerformPrintBonus.prototype.confirmGainIdeas = function () {
        // Get all selected ideas from supply
        var selectedIdeasFromSupply = dojo.query(".aoc-supply-idea-selection");
        // Initialize string to store idea ids in comma separated list
        var selectedIdeasFromSupplyGenres = "";
        // For each idea, add its id to the string
        for (var i = 0; i < selectedIdeasFromSupply.length; i++) {
            var idea = selectedIdeasFromSupply[i];
            if (i == 0) {
                selectedIdeasFromSupplyGenres += this.game.getGenreId(idea.id.split("-")[3]);
            }
            else {
                selectedIdeasFromSupplyGenres +=
                    "," + this.game.getGenreId(idea.id.split("-")[3]);
            }
        }
        // Send the idea ids to the server
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_CONFIRM_GAIN_BONUS_IDEAS, {
            ideas: selectedIdeasFromSupplyGenres,
        });
    };
    /**
     * Creates a div to hold an idea selection.
     *
     * @param idNum the id number of the div
     */
    PerformPrintBonus.prototype.createIdeaSelectionDiv = function (idNum) {
        // Create div for idea selection
        var ideaSelectionDiv = '<div id="aoc-supply-idea-selection-container-' +
            idNum +
            '" class="aoc-selection-container"><i id="aoc-idea-cancel-' +
            idNum +
            '" class="fa fa-lg fa-times-circle aoc-start-idea-remove aoc-hidden"></i></div>';
        // Add div to page
        this.game.createHtml(ideaSelectionDiv, "aoc-select-supply-idea-containers");
        // Add click listener to cancel button
        this.connections["aoc-idea-cancel-" + idNum] = dojo.connect(dojo.byId("aoc-idea-cancel-" + idNum), "onclick", dojo.hitch(this, "removeIdea", idNum));
    };
    /**
     * Creates possible idea actions from supply.
     */
    PerformPrintBonus.prototype.createIdeaTokensFromSupplyActions = function () {
        // Create div for idea token selection
        var ideaTokenSelectionDiv = "<div id='aoc-idea-token-selection' class='aoc-action-panel-row'></div>";
        this.game.createHtml(ideaTokenSelectionDiv, "page-title");
        // Get all genres
        var genres = this.game.getGenres();
        // For each genre, create a button
        for (var key in genres) {
            var genre = genres[key];
            var ideaTokenDiv = "<div id='aoc-select-supply-idea-token-" +
                genre +
                "' class='aoc-idea-token aoc-idea-token-" +
                genre +
                "'></div>";
            this.game.createHtml(ideaTokenDiv, "aoc-idea-token-selection");
            this.connections["aoc-select-supply-idea-token-" + genre] = dojo.connect(dojo.byId("aoc-select-supply-idea-token-" + genre), "onclick", dojo.hitch(this, "selectIdeaFromSupply", genre));
        }
        // Create divs for idea selection containers
        var selectionBoxesDiv = "<div id='aoc-select-supply-idea-containers' class='aoc-select-containers'></div>";
        this.game.createHtml(selectionBoxesDiv, "aoc-idea-token-selection");
        this.createIdeaSelectionDiv(1);
        this.createIdeaSelectionDiv(2);
    };
    /**
     * Gets the first empty idea selection div.
     *
     * @returns the first empty idea selection div or null if there are no empty idea selection divs
     */
    PerformPrintBonus.prototype.getFirstEmptyIdeaSelectionDiv = function () {
        // Get all idea selection divs
        var allDivs = dojo.query(".aoc-selection-container");
        // For each idea selection div, if it has no children, return it
        for (var i = 0; i < allDivs.length; i++) {
            var div = allDivs[i];
            if (div.children.length == 1) {
                return div;
            }
        }
        // If there are no empty idea selection divs, return null
        return null;
    };
    /**
     * Removes an idea from the idea selection div.
     *
     * @param slotId the id of the idea selection div
     */
    PerformPrintBonus.prototype.removeIdea = function (slotId) {
        // Remove idea from selection div
        var ideaDiv = dojo.byId("aoc-selected-idea-box-" + slotId);
        ideaDiv.remove();
        // Hide cancel button
        dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", true);
        // Set status of confirmation button
        this.setButtonConfirmationStatus();
    };
    /**
     * Called when a player clicks on an idea on the supply.
     *
     * @param genre the genre of the idea
     */
    PerformPrintBonus.prototype.selectIdeaFromSupply = function (genre) {
        // Get first empty idea selection div
        var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();
        // If there are no empty idea selection divs, return
        if (firstEmptySelectionDiv == null) {
            return;
        }
        // Get id of idea selection div
        var slotId = firstEmptySelectionDiv.id.split("-")[5];
        // Create div for selected idea
        var tokenDiv = '<div id="aoc-selected-idea-box-' +
            slotId +
            '"><div id="aoc-selected-idea-' +
            genre +
            '" class="aoc-supply-idea-selection aoc-idea-token aoc-idea-token-' +
            genre +
            '"></div></div>';
        // Add div to the idea selection div
        this.game.createHtml(tokenDiv, firstEmptySelectionDiv.id);
        // Unhide cancel button
        dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", false);
        // Set status of confirmation button
        this.setButtonConfirmationStatus();
    };
    /**
     * Sets the status of the confirmation button.
     * If all idea selection divs are full and all possible ideas from board are selected, enable the confirmation button.
     * If either are not true, disable the confirmation button.
     */
    PerformPrintBonus.prototype.setButtonConfirmationStatus = function () {
        var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();
        if (firstEmptySelectionDiv == null) {
            dojo.addClass("aoc-confirm-gain-ideas", "aoc-button");
            dojo.removeClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
        }
        else {
            dojo.addClass("aoc-confirm-gain-ideas", "aoc-button-disabled");
            dojo.removeClass("aoc-confirm-gain-ideas", "aoc-button");
        }
    };
    return PerformPrintBonus;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformPrintMastery.ts
 *
 * AgeOfComics perform print fulfill orders state
 *
 * State vars:
 * - game: game object reference
 *
 */
var PerformPrintFulfillOrders = /** @class */ (function () {
    function PerformPrintFulfillOrders(game) {
        this.game = game;
    }
    PerformPrintFulfillOrders.prototype.onEnteringState = function (stateArgs) { };
    PerformPrintFulfillOrders.prototype.onLeavingState = function () { };
    PerformPrintFulfillOrders.prototype.onUpdateActionButtons = function (stateArgs) { };
    return PerformPrintFulfillOrders;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformPrintMastery.ts
 *
 * AgeOfComics perform print mastery state
 *
 * State vars:
 * - game: game object reference
 *
 */
var PerformPrintMastery = /** @class */ (function () {
    function PerformPrintMastery(game) {
        this.game = game;
    }
    PerformPrintMastery.prototype.onEnteringState = function (stateArgs) { };
    PerformPrintMastery.prototype.onLeavingState = function () { };
    PerformPrintMastery.prototype.onUpdateActionButtons = function (stateArgs) { };
    return PerformPrintMastery;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformPrintMastery.ts
 *
 * AgeOfComics perform print upgrade state
 *
 * State vars:
 * - game: game object reference
 *
 */
var PerformPrintUpgrade = /** @class */ (function () {
    function PerformPrintUpgrade(game) {
        this.game = game;
        this.connections = {};
        this.placedCubes = 0;
        this.cubeToMove = 0;
    }
    PerformPrintUpgrade.prototype.onEnteringState = function (stateArgs) {
        if (stateArgs.isCurrentPlayerActive) {
            var player = stateArgs.args.player;
            if (player.cubeOneLocation > 0) {
                this.placedCubes++;
            }
            if (player.cubeTwoLocation > 0) {
                this.placedCubes++;
            }
            if (player.cubeThreeLocation > 0) {
                this.placedCubes++;
            }
            if (this.placedCubes < 3) {
                this.cubeToMove = this.placedCubes + 1;
                this.highlightUpgradableActions(stateArgs.args.upgradableActions);
            }
        }
    };
    PerformPrintUpgrade.prototype.onLeavingState = function () {
        dojo.query(".aoc-clickable").removeClass("aoc-clickable");
        for (var _i = 0, _a = Object.values(this.connections); _i < _a.length; _i++) {
            var connection = _a[_i];
            dojo.disconnect(connection);
        }
    };
    PerformPrintUpgrade.prototype.onUpdateActionButtons = function (stateArgs) { };
    PerformPrintUpgrade.prototype.highlightUpgradableActions = function (upgradableActions) {
        for (var _i = 0, upgradableActions_1 = upgradableActions; _i < upgradableActions_1.length; _i++) {
            var action = upgradableActions_1[_i];
            switch (action) {
                case 1:
                    this.highlightUpgradableAction(action, "aoc-action-hire-upgrade-spaces");
                    break;
                case 2:
                    this.highlightUpgradableAction(action, "aoc-action-develop-upgrade-spaces");
                    break;
                case 3:
                    this.highlightUpgradableAction(action, "aoc-action-ideas-upgrade-spaces");
                    break;
                case 4:
                    this.highlightUpgradableAction(action, "aoc-action-print-upgrade-spaces");
                    break;
                case 5:
                    this.highlightUpgradableAction(action, "aoc-action-royalites-upgrade-spaces");
                    break;
                case 6:
                    this.highlightUpgradableAction(action, "aoc-action-sales-upgrade-spaces");
                    break;
            }
        }
    };
    PerformPrintUpgrade.prototype.highlightUpgradableAction = function (actionKey, divId) {
        dojo.toggleClass(divId, "aoc-clickable");
        this.connections[actionKey] = dojo.connect(dojo.byId(divId), "onclick", dojo.hitch(this, "upgradeAction", actionKey));
    };
    PerformPrintUpgrade.prototype.upgradeAction = function (actionKey) {
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_PLACE_UPGRADE_CUBE, {
            actionKey: actionKey,
            cubeMoved: this.cubeToMove,
        });
    };
    return PerformPrintUpgrade;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformRoyalties.ts
 *
 * Age of Comic perform royalties state
 *
 * State vars:
 *  game: game object reference
 *
 */
var PerformRoyalties = /** @class */ (function () {
    function PerformRoyalties(game) {
        this.game = game;
    }
    PerformRoyalties.prototype.onEnteringState = function (stateArgs) { };
    PerformRoyalties.prototype.onLeavingState = function () { };
    PerformRoyalties.prototype.onUpdateActionButtons = function (stateArgs) { };
    return PerformRoyalties;
}());
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
var PerformSales = /** @class */ (function () {
    function PerformSales(game) {
        this.game = game;
    }
    PerformSales.prototype.onEnteringState = function (stateArgs) { };
    PerformSales.prototype.onLeavingState = function () { };
    PerformSales.prototype.onUpdateActionButtons = function (stateArgs) { };
    return PerformSales;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PlayerSetup.ts
 *
 * AgeOfComics player setup state
 *
 * State vars:
 *  game: game object reference
 *
 */
var PlayerSetup = /** @class */ (function () {
    function PlayerSetup(game) {
        this.game = game;
    }
    /**
     * Called when entering this state
     * Creates the starting items selection divs and events
     *
     * stateArgs:
     *  - isCurrentPlayerActive: true if this player is the active player
     *
     * args:
     * - startIdeas: number of starting ideas player can select
     *
     * @param stateArgs
     */
    PlayerSetup.prototype.onEnteringState = function (stateArgs) {
        // Hide the card market
        dojo.toggleClass("aoc-card-market", "aoc-hidden", true);
        if (stateArgs.isCurrentPlayerActive) {
            // Show the starting items selection divs
            dojo.style("aoc-select-start-items", "display", "block");
            var startIdeas = stateArgs.args.startIdeas;
            // Create a selection div for each starting idea the player can select
            for (var i = 1; i <= startIdeas; i++) {
                this.createIdeaSelectionDiv(i);
            }
            // Create the click events for the starting items
            this.createOnClickEvents(startIdeas);
        }
        // Adapt the viewport size
        this.game.adaptViewportSize();
    };
    /**
     * Called when leaving this state
     * Removes the starting items selection divs and events
     *
     */
    PlayerSetup.prototype.onLeavingState = function () {
        // Hide the starting items selection divs
        dojo.style("aoc-select-start-items", "display", "none");
        // Remove the css classes from the comics
        dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
        dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");
        // Empty the starting items selection divs
        dojo.empty("aoc-select-start-idea-containers");
        // Adapt the viewport size
        this.game.adaptViewportSize();
    };
    /**
     * Called when the action buttons are updated (start of the state)
     * Adds the confirm button
     *
     * stateArgs:
     *  - isCurrentPlayerActive: true if this player is the active player
     *
     * @param stateArgs
     */
    PlayerSetup.prototype.onUpdateActionButtons = function (stateArgs) {
        var _this = this;
        if (stateArgs.isCurrentPlayerActive) {
            // Add the confirm button
            gameui.addActionButton("aoc-confirm-starting-items", _("Confirm"), function () {
                _this.confirmStartingItems();
            });
            // Disable the confirm button and add custom style
            dojo.addClass("aoc-confirm-starting-items", "aoc-button-disabled");
            dojo.addClass("aoc-confirm-starting-items", "aoc-button");
        }
    };
    /**
     * Called when the confirm button is clicked
     * Sends the selected starting items to the server
     */
    PlayerSetup.prototype.confirmStartingItems = function () {
        // Disable the confirm button
        dojo.addClass("aoc-confirm-starting-items", "aoc-button-disabled");
        // Get the selected comic genre
        var selectedComic = dojo.query(".aoc-card-selected", "aoc-select-start-comic-genre")[0];
        // Get the genre key
        var selectedComicGenre = this.game.getGenreId(selectedComic.id.split("-")[4]);
        // Get the selected idea genres
        var selectedIdeas = dojo.query(".aoc-start-idea-selection");
        var selectedIdeaGenres = "";
        for (var i = 0; i < selectedIdeas.length; i++) {
            var idea = selectedIdeas[i];
            if (i == 0) {
                selectedIdeaGenres += this.game.getGenreId(idea.id.split("-")[3]);
            }
            else {
                selectedIdeaGenres += ",";
                selectedIdeaGenres += this.game.getGenreId(idea.id.split("-")[3]);
            }
        }
        // Send the selected starting items to the server
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_SELECT_START_ITEMS, {
            comic: selectedComicGenre,
            ideas: selectedIdeaGenres,
        });
    };
    /**
     * Creates the click events for the starting items
     *
     * @param startIdeas - number of starting ideas player can select
     */
    PlayerSetup.prototype.createOnClickEvents = function (startIdeas) {
        // For each genre
        var genres = this.game.getGenres();
        for (var key in genres) {
            var genre = genres[key];
            // Create a comic div and click event
            var comicDivId = "aoc-select-starting-comic-" + genre;
            dojo.connect(dojo.byId(comicDivId), "onclick", dojo.hitch(this, "selectComic", genre));
            // Create an idea div and click event
            var ideaDivId = "aoc-select-starting-idea-" + genre;
            dojo.connect(dojo.byId(ideaDivId), "onclick", dojo.hitch(this, "selectIdea", genre));
        }
        // Create cancel click events for each starting idea container
        for (var i = 1; i <= startIdeas; i++) {
            var ideaCancelId = "aoc-idea-cancel-" + i;
            dojo.connect(dojo.byId(ideaCancelId), "onclick", dojo.hitch(this, "removeIdea", i));
        }
    };
    /**
     * Creates a starting idea selection container
     *
     * @param idNum - the id number of the div
     */
    PlayerSetup.prototype.createIdeaSelectionDiv = function (idNum) {
        var ideaSelectionDiv = '<div id="aoc-selection-container-' +
            idNum +
            '" class="aoc-selection-container"><i id="aoc-idea-cancel-' +
            idNum +
            '" class="fa fa-lg fa-times-circle aoc-start-idea-remove aoc-hidden"></i></div>';
        this.game.createHtml(ideaSelectionDiv, "aoc-select-start-idea-containers");
    };
    /**
     * Gets the first empty starting idea selection div
     *
     * @returns the first empty starting idea selection div or null if none are empty
     */
    PlayerSetup.prototype.getFirstEmptyIdeaSelectionDiv = function () {
        var allDivs = dojo.query(".aoc-selection-container");
        for (var i = 0; i < allDivs.length; i++) {
            var div = allDivs[i];
            if (div.children.length == 1) {
                return div;
            }
        }
        return null;
    };
    /**
     * Removes an idea from an idea selection container
     *
     * @param slotId - the id number of the container
     */
    PlayerSetup.prototype.removeIdea = function (slotId) {
        var ideaDiv = dojo.byId("aoc-selected-idea-box-" + slotId);
        ideaDiv.remove();
        dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", true);
        // Set confirm button status
        this.setButtonConfirmationStatus();
    };
    /**
     * Selects a comic genre
     *
     * @param genre - the genre of the comic
     */
    PlayerSetup.prototype.selectComic = function (genre) {
        // Remove the selected and unselected classes from all comics
        dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
        dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");
        // Add the selected class to the selected comic
        var divId = "aoc-select-starting-comic-" + genre;
        dojo.addClass(divId, "aoc-card-selected");
        // Add the unselected class to all other comics
        var allComics = dojo.byId("aoc-select-start-comic-genre").children;
        for (var i = 0; i < allComics.length; i++) {
            var comic = allComics[i];
            if (comic.id != divId) {
                dojo.toggleClass(comic.id, "aoc-card-unselected", true);
            }
        }
        // Set confirm button status
        this.setButtonConfirmationStatus();
    };
    /**
     * Selects an idea genre
     *
     * @param genre - the genre of the idea
     */
    PlayerSetup.prototype.selectIdea = function (genre) {
        // Get the first empty starting idea selection div
        var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();
        // If there are no empty starting idea selection divs, return
        if (firstEmptySelectionDiv == null) {
            return;
        }
        // Get the id number of the empty starting idea selection div
        var slotId = firstEmptySelectionDiv.id.split("-")[3];
        // Create a matching idea token and add it to the empty starting idea selection div
        var tokenDiv = '<div id="aoc-selected-idea-box-' +
            slotId +
            '"><div id="aoc-selected-idea-' +
            genre +
            '" class="aoc-start-idea-selection aoc-idea-token aoc-idea-token-' +
            genre +
            '"></div></div>';
        this.game.createHtml(tokenDiv, firstEmptySelectionDiv.id);
        // Show the cancel button for the starting idea selection div
        dojo.toggleClass("aoc-idea-cancel-" + slotId, "aoc-hidden", false);
        // Set confirm button status
        this.setButtonConfirmationStatus();
    };
    /**
     * Sets the confirmation button status
     * Disables the button if there are no empty starting idea selection divs or no selected comic
     */
    PlayerSetup.prototype.setButtonConfirmationStatus = function () {
        // Get the first empty starting idea selection div
        var firstEmptySelectionDiv = this.getFirstEmptyIdeaSelectionDiv();
        // Get the selected comic
        var selectedComic = dojo.query(".aoc-card-selected", "aoc-select-start-comic-genre");
        // If there are no empty starting idea selection divs and there is a selected comic, enable the confirm button
        if (firstEmptySelectionDiv == null && selectedComic.length == 1) {
            dojo.toggleClass("aoc-confirm-starting-items", "aoc-button-disabled", false);
        }
        else {
            // Otherwise, disable the confirm button
            dojo.toggleClass("aoc-confirm-starting-items", "aoc-button-disabled", true);
        }
    };
    return PlayerSetup;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PlayerTurn.ts
 *
 * AgeOfComics player turn state
 *
 * State vars:
 *  game: game object reference
 *  connections: array of dojo connections
 *
 */
var PlayerTurn = /** @class */ (function () {
    function PlayerTurn(game) {
        this.game = game;
        this.connections = [];
    }
    PlayerTurn.prototype.onEnteringState = function (stateArgs) { };
    /**
     * On leaving the player turn state, remove all the action buttons and click events
     */
    PlayerTurn.prototype.onLeavingState = function () {
        dojo.query(".aoc-clickable").removeClass("aoc-clickable");
        dojo.forEach(this.connections, dojo.disconnect);
    };
    /**
     * On update state, highlight the action spaces that are available to the player
     *
     * stateArgs:
     *  - isCurrentPlayerActive: true if the current player is the active player
     *
     * args:
     *  - hireActionSpace: the next available action space number for the hire action
     *  - developActionSpace: the next available action space number for the develop action
     *  - ideasActionSpace: the next available action space number for the ideas action
     *  - printActionSpace: the next available action space number for the print action
     *  - royaltiesActionSpace: the next available action space number for the royalties action
     *  - salesActionSpace: the next available action space number for the sales action
     *
     */
    PlayerTurn.prototype.onUpdateActionButtons = function (stateArgs) {
        if (stateArgs.isCurrentPlayerActive) {
            this.highlightInteractiveActionElements("hire", "Hire", stateArgs.args.hireActionSpace);
            this.highlightInteractiveActionElements("develop", "Develop", stateArgs.args.developActionSpace);
            this.highlightInteractiveActionElements("ideas", "Ideas", stateArgs.args.ideasActionSpace);
            this.highlightInteractiveActionElements("print", "Print", stateArgs.args.printActionSpace);
            this.highlightInteractiveActionElements("royalties", "Royalties", stateArgs.args.royaltiesActionSpace);
            this.highlightInteractiveActionElements("sales", "Sales", stateArgs.args.salesActionSpace);
        }
    };
    /**
     * Highlight the action board element and add the click event
     *
     * @param actionType the type of action
     * @param actionButtonText the text to display on the action button
     * @param actionSpace the action space number
     */
    PlayerTurn.prototype.highlightInteractiveActionElements = function (actionType, actionButtonText, actionSpace) {
        var _this = this;
        // Get the action board element and action button div ids
        var actionButtonDivId = "aoc-take-" + actionType + "-action";
        var actionBoardElementId = "aoc-action-" + actionType;
        // Create the action button
        gameui.addActionButton(actionButtonDivId, _(actionButtonText), function () {
            dojo.setAttr(actionButtonDivId, "data-action-space", actionSpace);
            _this.selectAction(actionSpace);
        });
        dojo.addClass(actionButtonDivId, "aoc-button");
        // If the action space is 0 the player can't perform this action, disable the action button
        if (actionSpace == 0) {
            dojo.addClass(actionButtonDivId, "aoc-button-disabled");
        }
        else {
            // Highlight the action board element
            dojo.addClass(actionBoardElementId, "aoc-clickable");
            dojo.setAttr(actionBoardElementId, "data-action-space", actionSpace);
            // Add the click events
            this.connections.push(dojo.connect(dojo.byId(actionBoardElementId), "onclick", dojo.hitch(this, this.selectAction, actionSpace)));
        }
    };
    /**
     * Select an action space, send the action to the server
     *
     * @param actionSpace the action space number
     */
    PlayerTurn.prototype.selectAction = function (actionSpace) {
        this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_SELECT_ACTION_SPACE, {
            actionSpace: actionSpace,
        });
    };
    return PlayerTurn;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * StartNewRound.ts
 *
 * AgeOfComics start new round state
 *
 */
var StartNewRound = /** @class */ (function () {
    function StartNewRound(game) {
        this.game = game;
    }
    StartNewRound.prototype.onEnteringState = function (stateArgs) { };
    StartNewRound.prototype.onLeavingState = function () { };
    StartNewRound.prototype.onUpdateActionButtons = function (stateArgs) { };
    return StartNewRound;
}());
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * State.ts
 *
 * Interface for a game state
 *
 */
