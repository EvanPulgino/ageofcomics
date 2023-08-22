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
    console.log("game constructor");

    this.isDebug = window.location.host == "studio.boardgamearena.com";
    this.debug = this.isDebug ? console.info.bind(window.console) : () => {};
    this.curstate = null;
    this.pendingUpdate = false;
    this.currentPlayerWasActive = false;
    this.gameState = new GameState();
  }

  /**
   * UI setup entry point
   *
   * @param {object} gamedatas
   */
  setup(gamedata: any) {
    this.debug("Starting game setup", gameui);
    this.debug("Game data", gamedata);
    this.defineGlobalConstants(gamedata.constants);
  }

  /**
   * Gives javascript access to PHP defined constants
   *
   * @param {object} constants - constants defined in PHP
   */
  defineGlobalConstants(constants: any): void {
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
   */
  onEnteringState(stateName: string, args: { args: any }) {
    this.debug("onEnteringState: " + stateName, args, this.debugStateInfo());
    this.curstate = stateName;
    // Call appropriate method
    args["isCurrentPlayerActive"] = gameui.isCurrentPlayerActive();
    this.gameState[stateName].onEnteringState(this, args);

    if (this.pendingUpdate) {
      this.onUpdateActionButtons(stateName, args);
      this.pendingUpdate = false;
    }
  }

  /**
   * Called when game leaves a state
   *
   * @param {string} stateName - name of the state
   */
  onLeavingState(stateName: string) {
    this.debug("onLeavingState: " + stateName, this.debugStateInfo());
    this.currentPlayerWasActive = false;
    this.gameState[stateName].onLeavingState(this);
  }

  /**
   * Builds action buttons on state change
   *
   * @param {string} stateName - name of the state
   * @param {object} args - args passed to the state
   */

  onUpdateActionButtons(stateName: string, args: any) {
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
      this.debug(
        "onUpdateActionButtons: " + stateName,
        args,
        this.debugStateInfo()
      );
      this.currentPlayerWasActive = true;
      // Call appropriate method
      this.gameState[stateName].onUpdateActionButtons(this, args);
    } else {
      this.currentPlayerWasActive = false;
    }
  }

  /**
   * Get info about current state
   *
   * @returns {object} state info
   */
  debugStateInfo() {
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
   */
  ajaxcallwrapper(action: string, args?: any, handler?: (err: any) => void) {
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
   * @returns div element
   */
  createHtml(divstr: string, location?: string) {
    const tempHolder = document.createElement("div");
    tempHolder.innerHTML = divstr;
    const div = tempHolder.firstElementChild;
    const parentNode = document.getElementById(location);
    if (parentNode) parentNode.appendChild(div);
    return div;
  }

  /**
   * Creates a div and inserts it into the DOM
   * @param {string=} id - id of div
   * @param {string=} classes - classes to add to div
   * @param {string=} location - parent node to insert div into
   * @returns div element
   */
  createDiv(id?: string | undefined, classes?: string, location?: string) {
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

  getGenres(): string[] {
    return GENRES;
  };

  getGenreName(genreId: number) {
    return GENRES[genreId];
  }

  getPlayerColorAsString(playerColor: string): string {
    return PLAYER_COLORS[playerColor];
  }
}
