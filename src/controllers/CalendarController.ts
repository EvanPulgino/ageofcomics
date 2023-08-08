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

 class CalendarController extends GameBasics {

    setupCalendar(calendarTiles: any) : void {
        for (var key in calendarTiles) {
            this.createCalendarTile(calendarTiles[key]);
        }
    }

    createCalendarTile(calendarTile: any) : void {
        var calendarTileDiv = '<div id="aoc-calender-tile-' + calendarTile.id + '" class="aoc-calendar-tile ' + calendarTile.cssClass + '"></div>';
        this.createHtml(calendarTileDiv,"aoc-calendar-slot-" + calendarTile.position);
    }

 }