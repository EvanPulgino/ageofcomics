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
