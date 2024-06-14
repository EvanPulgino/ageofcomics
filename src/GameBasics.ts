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

// @ts-ignore
// @ts-nocheck
GameGui = /** @class */ (function () {
  function GameGui() {}
  return GameGui;
})();

class GameBasics extends GameGui {
  isDebug: boolean;
  debug: any;
  curstate: string | undefined;
  pendingUpdate: boolean;
  currentPlayerWasActive: boolean;
  gameState: GameState;
  constructor() {
    super();
    this.isDebug = window.location.host == "studio.boardgamearena.com";
    this.debug = this.isDebug ? console.info.bind(window.console) : () => {};
    this.debug("GameBasics constructor", this);
    this.curstate = null;
    this.pendingUpdate = false;
    this.currentPlayerWasActive = false;
    this.gameState = new GameState(this);
  }

  /**
   * Change the viewport size based on current window size
   * Called when window is resized and a few other places
   *
   * @returns {void}
   */
  adaptViewportSize(): void {
    var t = dojo.marginBox("aoc-overall");
    var r = t.w;
    var s = 1500;
    var height = dojo.marginBox("aoc-layout").h;
    var viewportWidth = dojo.window.getBox().w;
    var gameAreaWidth =
      viewportWidth < 980 ? viewportWidth : viewportWidth - 245;

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
   * @param {object} gamedata - game data
   * @returns {void}
   */
  setup(gamedata: object): void {
    this.debug("Game data", gamedata);
    this.defineGlobalConstants(gamedata.constants);
  }

  /**
   * Gives javascript access to PHP defined constants
   *
   * @param {object} constants - constants defined in PHP
   * @returns {void}
   */
  defineGlobalConstants(constants: object): void {
    for (var constant in constants) {
      if (!globalThis[constant]) {
        globalThis[constant] = constants[constant];
      }
    }
  }

  /**
   * Called when game enters a new state
   *
   * @param {string} stateName - name of the state
   * @param {object} args - args passed to the state
   * @returns {void}
   */
  onEnteringState(stateName: string, args: { args: any }): void {
    this.debug("onEnteringState: " + stateName, args, this.debugStateInfo());
    this.adaptViewportSize();
    this.curstate = stateName;
    args["isCurrentPlayerActive"] = gameui.isCurrentPlayerActive();
    this.gameState[stateName].onEnteringState(args);

    if (this.pendingUpdate) {
      this.onUpdateActionButtons(stateName, args);
      this.pendingUpdate = false;
    }
  }

  /**
   * Called when game leaves a state
   *
   * @param {string} stateName - name of the state
   * @returns {void}
   */
  onLeavingState(stateName: string): void {
    this.debug("onLeavingState: " + stateName, this.debugStateInfo());
    this.currentPlayerWasActive = false;
    this.gameState[stateName].onLeavingState();
    this.adaptViewportSize();
  }

  /**
   * Builds action buttons on state change
   *
   * @param {string} stateName - name of the state
   * @param {object} args - args passed to the state
   * @returns {void}
   */

  onUpdateActionButtons(stateName: string, args: any): void {
    if (this.curstate != stateName) {
      // delay firing this until onEnteringState is called so they always called in same order
      this.pendingUpdate = true;
      return;
    }
    this.pendingUpdate = false;
    if (
      gameui.isCurrentPlayerActive() &&
      this.currentPlayerWasActive == false
    ) {
      var stateArgs = [];
      if (args.id === undefined) {
        stateArgs["isCurrentPlayerActive"] = gameui.isCurrentPlayerActive();
        stateArgs["args"] = args;
      } else {
        stateArgs = args;
      }
      this.debug(
        "onUpdateActionButtons: " + stateName,
        stateArgs,
        this.debugStateInfo()
      );
      this.currentPlayerWasActive = true;
      // Call appropriate method
      this.gameState[stateName].onUpdateActionButtons(stateArgs);
    } else {
      this.currentPlayerWasActive = false;
    }
  }

  /**
   * Get info about current state
   *
   * @returns {object} state info
   */
  debugStateInfo(): object {
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
  }

  /**
   * A wrapper to make AJAX calls to the game server
   *
   * @param {string} action - name of the action
   * @param {object=} args - args passed to the action
   * @param {requestCallback=} handler - callback function
   * @returns {void}
   */
  ajaxcallwrapper(
    action: string,
    args?: any,
    handler?: (err: any) => void
  ): void {
    if (!args) {
      args = {};
    }
    args.lock = true;

    if (gameui.checkAction(action)) {
      gameui.ajaxcall(
        "/" +
          gameui.game_name +
          "/" +
          gameui.game_name +
          "/" +
          action +
          ".html",
        args, //
        gameui,
        (result) => {},
        handler
      );
    }
  }

  /**
   * Creates and inserts HTML into the DOM
   *
   * @param {string} divstr - div to create
   * @param {string=} location - parent node to insert div into
   * @returns {any} div element
   */
  createHtml(divstr: string, location?: string): any {
    const tempHolder = document.createElement("div");
    tempHolder.innerHTML = divstr;
    const div = tempHolder.firstElementChild;
    const parentNode = document.getElementById(location);
    if (parentNode) parentNode.appendChild(div);
    return div;
  }

  /**
   * Creates a div and inserts it into the DOM
   *
   * @param {string=} id - id of div
   * @param {string=} classes - classes to add to div
   * @param {string=} location - parent node to insert div into
   * @returns {any}
   */
  createDiv(id?: string | undefined, classes?: string, location?: string): any {
    const div = document.createElement("div");
    if (id) div.id = id;
    if (classes) div.classList.add(...classes.split(" "));
    const parentNode = document.getElementById(location);
    if (parentNode) parentNode.appendChild(div);
    return div;
  }

  /**
   * Calls a function if it exists
   *
   * @param {string} methodName - name of the function
   * @param {object} args - args passed to the function
   * @returns
   */
  callfn(methodName: string, args: any) {
    if (this[methodName] !== undefined) {
      this.debug("Calling " + methodName, args);
      return this[methodName](args);
    }
    return undefined;
  }

  /** @Override onScriptError from gameui */
  onScriptError(msg: any, url: any, linenumber: any) {
    if (gameui.page_is_unloading) {
      // Don't report errors during page unloading
      return;
    }
    // In anycase, report these errors in the console
    console.error(msg);
    // cannot call super - dojo still have to used here
    //super.onScriptError(msg, url, linenumber);
    return this.inherited(arguments);
  }

  /**
   * Get a map of all genre keys and values
   *
   * @returns {object} map of genre keys and values
   */
  getGenres(): object {
    return GENRES;
  }

  /**
   * Gets a genre key from a genre gam
   *
   * @param genre
   * @returns {number} genre id
   */
  getGenreId(genre: string): number {
    for (var key in GENRES) {
      if (GENRES[key] == genre) {
        return parseInt(key);
      }
    }
  }

  /**
   * Gets a genre name from a genre id
   *
   * @param genreId
   * @returns {string} genre name
   */
  getGenreName(genreId: number) {
    return GENRES[genreId];
  }

  /**
   * Gets the name of a player's color from its hex value
   *
   * @param playerColor
   * @returns {string} player color name
   */
  getPlayerColorAsString(playerColor: string): string {
    return PLAYER_COLORS[playerColor];
  }
}
