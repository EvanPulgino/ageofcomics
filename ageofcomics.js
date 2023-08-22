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
        console.log("game constructor");
        _this.isDebug = window.location.host == "studio.boardgamearena.com";
        _this.debug = _this.isDebug ? console.info.bind(window.console) : function () { };
        _this.curstate = null;
        _this.pendingUpdate = false;
        _this.currentPlayerWasActive = false;
        _this.gameState = new GameState();
        return _this;
    }
    /**
     * UI setup entry point
     *
     * @param {object} gamedatas
     */
    GameBasics.prototype.setup = function (gamedata) {
        this.debug("Starting game setup", gameui);
        this.debug("Game data", gamedata);
        this.defineGlobalConstants(gamedata.constants);
    };
    /**
     * Gives javascript access to PHP defined constants
     *
     * @param {object} constants - constants defined in PHP
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
     */
    GameBasics.prototype.onEnteringState = function (stateName, args) {
        this.debug("onEnteringState: " + stateName, args, this.debugStateInfo());
        this.curstate = stateName;
        // Call appropriate method
        args["isCurrentPlayerActive"] = gameui.isCurrentPlayerActive();
        this.gameState[stateName].onEnteringState(this, args);
        if (this.pendingUpdate) {
            this.onUpdateActionButtons(stateName, args);
            this.pendingUpdate = false;
        }
    };
    /**
     * Called when game leaves a state
     *
     * @param {string} stateName - name of the state
     */
    GameBasics.prototype.onLeavingState = function (stateName) {
        this.debug("onLeavingState: " + stateName, this.debugStateInfo());
        this.currentPlayerWasActive = false;
        this.gameState[stateName].onLeavingState(this);
    };
    /**
     * Builds action buttons on state change
     *
     * @param {string} stateName - name of the state
     * @param {object} args - args passed to the state
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
            this.gameState[stateName].onUpdateActionButtons(this, args);
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
     * @returns div element
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
     * @param {string=} id - id of div
     * @param {string=} classes - classes to add to div
     * @param {string=} location - parent node to insert div into
     * @returns div element
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
    GameBasics.prototype.getGenres = function () {
        return GENRES;
    };
    ;
    GameBasics.prototype.getGenreName = function (genreId) {
        return GENRES[genreId];
    };
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
        _this.gameController = new GameController();
        _this.playerController = new PlayerController();
        _this.calendarController = new CalendarController();
        _this.cardController = new CardController();
        _this.editorController = new EditorController();
        _this.masteryController = new MasteryController();
        _this.miniComicController = new MiniComicController();
        _this.ripoffController = new RipoffController();
        _this.salesOrderController = new SalesOrderController();
        _this.ticketController = new TicketController();
        dojo.connect(window, "onresize", _this, dojo.hitch(_this, "adaptViewportSize"));
        return _this;
    }
    GameBody.prototype.adaptViewportSize = function () {
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
     * @param {object} gamedata - current game data used to initialize UI
     */
    GameBody.prototype.setup = function (gamedata) {
        _super.prototype.setup.call(this, gamedata);
        this.gameController.setup(gamedata);
        this.playerController.setupPlayers(gamedata.playerInfo);
        this.calendarController.setupCalendar(gamedata.calendarTiles);
        this.cardController.setupPlayerHands(gamedata.playerHands);
        this.editorController.setupEditors(gamedata.editors);
        this.masteryController.setupMasteryTokens(gamedata.mastery);
        this.miniComicController.setupMiniComics(gamedata.miniComics);
        this.ripoffController.setupRipoffCards(gamedata.ripoffCards);
        this.salesOrderController.setupSalesOrders(gamedata.salesOrders);
        this.ticketController.setupTickets(gamedata.ticketSupply);
        this.setupNotifications();
        this.debug("Ending game setup");
    };
    /**
     * Handle button click events
     *
     * @param {object} event - event triggered
     */
    GameBody.prototype.onButtonClick = function (event) {
        this.debug("onButtonClick", event);
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
    };
    /**
     * Handle 'message' notification
     *
     * @param {object} notif - notification data
     */
    GameBody.prototype.notif_message = function (notif) {
        this.debug("notif", notif);
    };
    /**
     * Handle 'setupMoney' notification
     *
     * @param {object} notif - notification data
     */
    GameBody.prototype.notif_setupMoney = function (notif) {
        this.playerController.adjustMoney(notif.args.player, notif.args.money);
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
    function GameState() {
        this.gameEnd = new GameEnd();
        this.gameSetup = new GameSetup();
        this.playerSetup = new PlayerSetup();
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
 */
var CalendarController = /** @class */ (function (_super) {
    __extends(CalendarController, _super);
    function CalendarController() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    CalendarController.prototype.setupCalendar = function (calendarTiles) {
        for (var key in calendarTiles) {
            this.createCalendarTile(calendarTiles[key]);
        }
    };
    CalendarController.prototype.createCalendarTile = function (calendarTile) {
        var calendarTileDiv = '<div id="aoc-calender-tile-' + calendarTile.id + '" class="aoc-calendar-tile ' + calendarTile.cssClass + '"></div>';
        this.createHtml(calendarTileDiv, "aoc-calendar-slot-" + calendarTile.position);
    };
    return CalendarController;
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
 * CardController.ts
 *
 */
var CardController = /** @class */ (function (_super) {
    __extends(CardController, _super);
    function CardController() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    CardController.prototype.setupPlayerHands = function (playerHands) {
        for (var player_id in playerHands) {
            var hand = playerHands[player_id];
            for (var i in hand) {
                var card = hand[i];
                this.createCard(card);
            }
        }
    };
    CardController.prototype.createCard = function (card) {
        switch (card.typeId) {
            case 1:
                this.createCreativeCard(card);
                break;
            case 2:
                this.createCreativeCard(card);
                break;
            case 3:
                this.createComicCard(card);
                break;
        }
    };
    CardController.prototype.createComicCard = function (card) {
        var cardDiv = '<div id="aoc-card-' +
            card.id +
            '" class="aoc-comic-card ' +
            card.cssClass +
            '"></div>';
        if (card.location == globalThis.LOCATION_HAND) {
            this.createHtml(cardDiv, "aoc-hand-" + card.playerId);
        }
    };
    CardController.prototype.createCreativeCard = function (card) {
        var cardDiv = '<div id="aoc-card-' +
            card.id +
            '" class="aoc-creative-card ' +
            card.cssClass +
            '"></div>';
        if (card.location == globalThis.LOCATION_HAND) {
            this.createHtml(cardDiv, "aoc-hand-" + card.playerId);
        }
    };
    return CardController;
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
 * EditorController.ts
 *
 */
var EditorController = /** @class */ (function (_super) {
    __extends(EditorController, _super);
    function EditorController() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    EditorController.prototype.setupEditors = function (editors) {
        for (var key in editors) {
            this.createEditor(editors[key]);
        }
    };
    EditorController.prototype.createEditor = function (editor) {
        var editorDiv = '<div id="aoc-editor-' + editor.id + '" class="aoc-editor ' + editor.cssClass + '"></div>';
        if (editor.locationId == globalThis.LOCATION_EXTRA_EDITOR) {
            var color = this.getPlayerColorAsString(editor.color);
            this.createHtml(editorDiv, "aoc-extra-editor-space-" + color);
        }
        else if (editor.locationId == globalThis.LOCATION_PLAYER_AREA) {
            var color = this.getPlayerColorAsString(editor.color);
            this.createHtml(editorDiv, "aoc-editor-container-" + editor.playerId);
        }
    };
    return EditorController;
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
 * GameController.ts
 *
 */
var GameController = /** @class */ (function (_super) {
    __extends(GameController, _super);
    function GameController() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    GameController.prototype.setup = function (gamedata) {
        this.createNeededGameElements(gamedata);
        this.createIdeaTokensOnBoard(gamedata.ideasSpaceContents);
    };
    /**
     * Create game status panel
     * @param {object} gamedata - current game data used to initialize UI
     */
    GameController.prototype.createNeededGameElements = function (gamedata) {
        this.createGameStatusPanelHtml();
        this.createShowChartContainerHtml();
        this.createChartHtml(gamedata.playerInfo);
        this.createOnClickEvents();
    };
    GameController.prototype.createGameStatusPanelHtml = function () {
        var gameStatusPanelHtml = '<div id="aoc-game-status-panel" class="player-board"><div id="aoc-game-status" class="player_board_content"><div id="aoc-game-status-mastery-container" class="aoc-game-status-row"></div><div id="aoc-button-row" class="aoc-game-status-row"><a id="aoc-show-chart-button" class="aoc-status-button" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-chart-simple"></i></a><a id="aoc-carousel-button" class="aoc-status-button" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-arrows-left-right-to-line"></i></a><a id="aoc-list-button" class="aoc-status-button" href="#"><i class="aoc-icon-size fa6 fa6-solid fa6-list"></i></a></div></div></div>';
        this.createHtml(gameStatusPanelHtml, "player_boards");
    };
    GameController.prototype.createShowChartContainerHtml = function () {
        var showChartContainerHtml = '<div id="aoc-show-chart-container"><div id="aoc-show-chart-underlay"></div><div id="aoc-show-chart-wrapper"></div></div>';
        this.createHtml(showChartContainerHtml, "overall-content");
    };
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
        this.createHtml(chartHtml, "aoc-show-chart-wrapper");
    };
    GameController.prototype.createOnClickEvents = function () {
        dojo.connect($("aoc-show-chart-button"), "onclick", this, "showChart");
        dojo.connect($("aoc-show-chart-close"), "onclick", this, "hideChart");
        dojo.connect($("aoc-carousel-button"), "onclick", this, "carouselView");
        dojo.connect($("aoc-list-button"), "onclick", this, "listView");
        dojo.query(".fa6-circle-right").connect("onclick", this, "nextPlayer");
        dojo.query(".fa6-circle-left").connect("onclick", this, "previousPlayer");
    };
    GameController.prototype.createIdeaTokensOnBoard = function (ideasSpaceContents) {
        for (var key in ideasSpaceContents) {
            var genreSpace = ideasSpaceContents[key];
            this.createIdeaTokenOnBoard(key, genreSpace);
        }
    };
    GameController.prototype.createIdeaTokenOnBoard = function (genreId, exists) {
        if (exists) {
            var genre = this.getGenreName(genreId);
            var ideaTokenDiv = '<div id="aoc-idea-token-' +
                genre +
                '" class="aoc-idea-token aoc-idea-token-' +
                genre +
                '"></div>';
            this.createHtml(ideaTokenDiv, "aoc-action-ideas-" + genre);
        }
    };
    GameController.prototype.showChart = function () {
        dojo.style("aoc-show-chart-container", "display", "block");
    };
    GameController.prototype.hideChart = function () {
        dojo.style("aoc-show-chart-container", "display", "none");
    };
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
    };
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
    };
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
 * MasteryController.ts
 *
 */
var MasteryController = /** @class */ (function (_super) {
    __extends(MasteryController, _super);
    function MasteryController() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    MasteryController.prototype.setupMasteryTokens = function (masteryTokens) {
        for (var key in masteryTokens) {
            this.createMasteryToken(masteryTokens[key]);
        }
    };
    MasteryController.prototype.createMasteryToken = function (masteryToken) {
        var masteryTokenDiv = '<div id="aoc-mastery-token-' + masteryToken.id + '" class="aoc-mastery-token aoc-mastery-token-' + masteryToken.genre + '"></div>';
        if (masteryToken.playerId == 0) {
            this.createHtml(masteryTokenDiv, "aoc-game-status-mastery-container");
        }
    };
    return MasteryController;
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
 * MiniComicController.ts
 *
 */
var MiniComicController = /** @class */ (function (_super) {
    __extends(MiniComicController, _super);
    function MiniComicController() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    MiniComicController.prototype.setupMiniComics = function (miniComics) {
        for (var key in miniComics) {
            this.createMiniComic(miniComics[key]);
        }
    };
    MiniComicController.prototype.createMiniComic = function (miniComic) {
        var miniComicDiv = '<div id="aoc-mini-comic-' + miniComic.id + '" class="aoc-mini-comic ' + miniComic.cssClass + '"></div>';
        if (miniComic.location == globalThis.LOCATION_SUPPLY) {
            this.createHtml(miniComicDiv, "aoc-mini-" + miniComic.type + "s-" + miniComic.genre);
        }
        if (miniComic.location == globalThis.LOCATION_CHART) {
            // TODO: Add to chart location
        }
    };
    return MiniComicController;
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
 * PlayerController.ts
 *
 */
var PlayerController = /** @class */ (function (_super) {
    __extends(PlayerController, _super);
    function PlayerController() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    PlayerController.prototype.setupPlayers = function (playerData) {
        this.playerCounter = {};
        for (var key in playerData) {
            this.createPlayerOrderToken(playerData[key]);
            this.createPlayerAgent(playerData[key]);
            this.createPlayerCubes(playerData[key]);
            this.createPlayerPanel(playerData[key]);
            this.createPlayerCounters(playerData[key]);
        }
    };
    PlayerController.prototype.adjustMoney = function (player, amount) {
        this.updatePlayerCounter(player, "money", amount);
    };
    PlayerController.prototype.createPlayerOrderToken = function (player) {
        var playerOrderTokenDiv = '<div id="aoc-player-order-token' +
            player.id +
            '" class="aoc-player-order-token aoc-player-order-token-' +
            player.colorAsText +
            '"></div>';
        this.createHtml(playerOrderTokenDiv, "aoc-player-order-space-" + player.turnOrder);
    };
    PlayerController.prototype.createPlayerAgent = function (player) {
        var playerAgentDiv = '<div id="aoc-agent' +
            player.id +
            '" class="aoc-agent aoc-agent-' +
            player.colorAsText +
            '"></div>';
        this.createHtml(playerAgentDiv, "aoc-map-agent-space-" + player.agentLocation);
    };
    PlayerController.prototype.createPlayerCubes = function (player) {
        this.createPlayerCubeOne(player);
        this.createPlayerCubeTwo(player);
        this.createPlayerCubeThree(player);
    };
    PlayerController.prototype.createPlayerCubeOne = function (player) {
        var cubeDiv = '<div id="aoc-player-cube-one-' +
            player.id +
            '" class="aoc-player-cube aoc-player-cube-' +
            player.colorAsText +
            '"></div>';
        if (player.cubeOneLocation == 5) {
            this.createHtml(cubeDiv, "aoc-cube-one-space-" + player.id);
        }
    };
    PlayerController.prototype.createPlayerCubeTwo = function (player) {
        var cubeDiv = '<div id="aoc-player-cube-two-' +
            player.id +
            '" class="aoc-player-cube aoc-player-cube-' +
            player.colorAsText +
            '"></div>';
        if (player.cubeOneLocation == 5) {
            this.createHtml(cubeDiv, "aoc-cube-two-space-" + player.id);
        }
    };
    PlayerController.prototype.createPlayerCubeThree = function (player) {
        var cubeDiv = '<div id="aoc-player-cube-three-' +
            player.id +
            '" class="aoc-player-cube aoc-player-cube-' +
            player.colorAsText +
            '"></div>';
        if (player.cubeOneLocation == 5) {
            this.createHtml(cubeDiv, "aoc-cube-three-space-" + player.id);
        }
    };
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
            '<div id="aoc-player-panel-other-' +
            player.id +
            '" class="aoc-player-panel-row">' +
            this.createPlayerPanelOtherSupplyDiv(player, "money") +
            this.createPlayerPanelOtherSupplyDiv(player, "point") +
            this.createPlayerPanelOtherSupplyDiv(player, "income") +
            "</div>" +
            "</div>";
        this.createHtml(playerPanelDiv, "player_board_" + player.id);
    };
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
    PlayerController.prototype.createPlayerPanelOtherSupplyDiv = function (player, supply) {
        var otherSupplyDiv;
        switch (supply) {
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
        }
        return otherSupplyDiv;
    };
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
        // TODO:calculate income
        this.createPlayerCounter(player, "income", 0);
    };
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
    PlayerController.prototype.updatePlayerCounter = function (player, counter, value) {
        var counterKey = counter;
        var counterPanel = "panel-" + counter;
        this.playerCounter[player.id][counterKey].incValue(value);
        this.playerCounter[player.id][counterPanel].incValue(value);
    };
    return PlayerController;
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
 * RipoffController.ts
 *
 */
var RipoffController = /** @class */ (function (_super) {
    __extends(RipoffController, _super);
    function RipoffController() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    RipoffController.prototype.setupRipoffCards = function (ripoffCards) {
        for (var key in ripoffCards) {
            this.createRipoffCard(ripoffCards[key]);
        }
    };
    RipoffController.prototype.createRipoffCard = function (ripoffCard) {
        var ripoffCardDiv = '<div id="aoc-ripoff-card-' + ripoffCard.id + '" class="aoc-ripoff-card ' + ripoffCard.cssClass + '"></div>';
        if (ripoffCard.location == globalThis.LOCATION_DECK) {
            this.createHtml(ripoffCardDiv, "aoc-ripoff-deck");
        }
    };
    return RipoffController;
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
 * SalesOrderController.ts
 *
 */
var SalesOrderController = /** @class */ (function (_super) {
    __extends(SalesOrderController, _super);
    function SalesOrderController() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    SalesOrderController.prototype.setupSalesOrders = function (salesOrders) {
        for (var key in salesOrders) {
            this.createSalesOrder(salesOrders[key]);
        }
    };
    SalesOrderController.prototype.createSalesOrder = function (salesOrder) {
        var salesOrderDiv = '<div id="aoc-salesorder-' + salesOrder.id + '" class="aoc-salesorder ' + salesOrder.cssClass + '"></div>';
        if (salesOrder.location == globalThis.LOCATION_MAP) {
            this.createHtml(salesOrderDiv, "aoc-map-order-space-" + salesOrder.locationArg);
        }
    };
    return SalesOrderController;
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
 * TicketController.ts
 *
 */
var TicketController = /** @class */ (function (_super) {
    __extends(TicketController, _super);
    function TicketController() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    TicketController.prototype.setupTickets = function (ticketCount) {
        for (var i = 1; i <= ticketCount; i++) {
            this.createTicket(i);
        }
    };
    TicketController.prototype.createTicket = function (ticketNum) {
        var ticketDiv = '<div id="aoc-ticket-' + ticketNum + '" class="aoc-ticket"></div>';
        this.createHtml(ticketDiv, "aoc-tickets-space");
    };
    return TicketController;
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
 * GameEnd.ts
 *
 * AgeOfComics game end state
 *
 */
var GameEnd = /** @class */ (function () {
    function GameEnd() {
    }
    GameEnd.prototype.onEnteringState = function (game, stateArgs) { };
    GameEnd.prototype.onLeavingState = function (game) { };
    GameEnd.prototype.onUpdateActionButtons = function (game, stateArgs) { };
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
 */
var GameSetup = /** @class */ (function () {
    function GameSetup() {
    }
    GameSetup.prototype.onEnteringState = function (game, stateArgs) { };
    GameSetup.prototype.onLeavingState = function (game) { };
    GameSetup.prototype.onUpdateActionButtons = function (game, stateArgs) { };
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
 * PlayerSetup.ts
 *
 * AgeOfComics player setup state
 *
 */
var PlayerSetup = /** @class */ (function () {
    function PlayerSetup() {
    }
    PlayerSetup.prototype.onEnteringState = function (game, stateArgs) {
        if (stateArgs.isCurrentPlayerActive) {
            dojo.style("aoc-select-start-items", "display", "block");
            var startIdeas = stateArgs.args.startIdeas;
            for (var i = 1; i <= startIdeas; i++) {
                this.createIdeaSelectionDiv(game, i);
            }
            this.createOnClickEvents(game.getGenres());
        }
    };
    PlayerSetup.prototype.onLeavingState = function (game) {
        dojo.style("aoc-select-start-items", "display", "none");
        dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
        dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");
    };
    PlayerSetup.prototype.onUpdateActionButtons = function (game, stateArgs) { };
    PlayerSetup.prototype.createOnClickEvents = function (genres) {
        for (var key in genres) {
            var genre = genres[key];
            var divId = "aoc-select-starting-" + genre;
            dojo.connect(dojo.byId(divId), "onclick", dojo.hitch(this, "selectComic", genre));
        }
    };
    PlayerSetup.prototype.createIdeaSelectionDiv = function (game, idNum) {
        var ideaSelectionDiv = '<div id="aoc-selection-container-' +
            idNum +
            '" class="aoc-selection-container"></div>';
        game.createHtml(ideaSelectionDiv, "aoc-select-containers");
    };
    PlayerSetup.prototype.selectComic = function (genre) {
        dojo.query(".aoc-card-selected").removeClass("aoc-card-selected");
        dojo.query(".aoc-card-unselected").removeClass("aoc-card-unselected");
        var divId = "aoc-select-starting-" + genre;
        dojo.addClass(divId, "aoc-card-selected");
        var allComics = dojo.byId("aoc-select-comic-genre").children;
        for (var i = 0; i < allComics.length; i++) {
            var comic = allComics[i];
            if (comic.id != divId) {
                dojo.addClass(comic.id, "aoc-card-unselected");
            }
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
 * State.ts
 *
 * Interface for a game state
 *
 */
