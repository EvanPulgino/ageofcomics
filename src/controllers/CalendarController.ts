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

class CalendarController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  /**
   * Set up the calendar by creating the tiles
   *
   * @param calendarTiles - the tiles to create
   */
  setupCalendar(calendarTiles: any): void {
    for (var key in calendarTiles) {
      this.createCalendarTile(calendarTiles[key]);
    }
  }

  /**
   * Create a calendar tile
   *
   * @param calendarTile - the tile to create
   */
  createCalendarTile(calendarTile: any): void {
    var calendarTileDiv =
      '<div id="aoc-calender-tile-' +
      calendarTile.id +
      '" class="aoc-calendar-tile ' +
      calendarTile.cssClass +
      '"></div>';
    this.ui.createHtml(
      calendarTileDiv,
      "aoc-calendar-slot-" + calendarTile.position
    );
  }

  /**
   * Flip a calendar tile face-up
   *
   * @param calendarTile - the tile to flip
   */
  flipCalendarTile(calendarTile: any): void {
    var calendarTileDiv = dojo.byId("aoc-calender-tile-" + calendarTile.id);
    dojo.removeClass(calendarTileDiv, "aoc-calendar-tile-facedown");
    dojo.addClass(calendarTileDiv, calendarTile.cssClass);
  }

  /**
   * Flip multiple calendar tiles face-up
   *
   * @param calendarTiles - the tiles to flip
   */
  flipCalendarTiles(calendarTiles: any): void {
    for (var key in calendarTiles) {
      this.flipCalendarTile(calendarTiles[key]);
    }
  }
}
