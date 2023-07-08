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
 */

// @ts-ignore
class GameBody extends GameBasics {
  states: any;
  constructor() {
    super();
    this.states = {};
    console.log("states", this.states);
  }

  /**
   * UI setup entry point
   *
   * @param {object} gamedatas
   */
  setup(gamedata: any) {
    super.setup(gamedata);
    this.setupNotifications();
    this.debug("Ending game setup");
  }

  /**
   * Handle button click events
   *
   * @param {object} event
   */
  onButtonClick(event: any): void {
    this.debug("onButtonClick", event);
  }

  /**
   * Handles changes when entering a new state
   *
   * @param stateName
   * @param args
   */
  enterState(stateName: string, args: any): void {
    this.debug("enterState", stateName, args);
    // this.states[stateName].onEnteringState(args);
  }

  /**
   * Handles changes when leaving a state
   *
   * @param stateName
   */
  leaveState(stateName: string): void {
    this.debug("leaveState", stateName);
    this.states[stateName].onLeavingState();
  }

  /**
   * Handles update action buttons on state change
   *
   * @param stateName
   * @param args
   */
  updateActionButtons(stateName: string, args: any): void {
    this.debug("updateActionButtons", stateName, args);
    this.states[stateName].onUpdateActionButtons(this, args);
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
