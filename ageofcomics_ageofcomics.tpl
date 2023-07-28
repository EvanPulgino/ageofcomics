{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    ageofcomics_ageofcomics.tpl

    This is the HTML template of your game.

    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.

    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format

    See your "view" PHP file to check how to set variables and control blocks

    Please REMOVE this comment before publishing your game on BGA
-->


<div id="aoc-gboard">
    <div id="aoc-layout">
        <div id="aoc-common-area">
            <div id="aoc-board" class="aoc-board-image aoc-board-section aoc-board">
                <div id="aoc-player-order">
                    <div id="aoc-player-order-space-1" class="aoc-player-order-space"></div>
                    <div id="aoc-player-order-space-2" class="aoc-player-order-space"></div>
                    <div id="aoc-player-order-space-3" class="aoc-player-order-space"></div>
                    <div id="aoc-player-order-space-4" class="aoc-player-order-space"></div>
                </div>
                <div id="aoc-calender">
                    <div id="aoc-calendar-slot-10" class="aoc-calendar-slot"></div>
                    <div id="aoc-calendar-slot-20" class="aoc-calendar-slot"></div>
                    <div id="aoc-calendar-slot-31" class="aoc-calendar-slot"></div>
                    <div id="aoc-calendar-slot-32" class="aoc-calendar-slot"></div>
                    <div id="aoc-calendar-slot-40" class="aoc-calendar-slot"></div>
                    <div id="aoc-calendar-slot-50" class="aoc-calendar-slot"></div>
                </div>
                <div id="aoc-action-ideas">
                    <div id="aoc-action-ideas-idea-spaces">
                        <div id="aoc-action-ideas-crime" class="aoc-action-ideas-idea-space"></div>
                        <div id="aoc-action-ideas-scifi" class="aoc-action-ideas-idea-space"></div>
                        <div id="aoc-action-ideas-superhero" class="aoc-action-ideas-idea-space"></div>
                        <div id="aoc-action-ideas-romance" class="aoc-action-ideas-idea-space"></div>
                        <div id="aoc-action-ideas-western" class="aoc-action-ideas-idea-space"></div>
                        <div id="aoc-action-ideas-horror" class="aoc-action-ideas-idea-space"></div>
                    </div>
                </div>
                <div id="aoc-mini-comics-section">
                    <div id="aoc-mini-comics">
                        <div id="aoc-mini-comics-crime" class="aoc-mini-comic-container aoc-mini-comic-space"></div>
                        <div id="aoc-mini-comics-horror" class="aoc-mini-comic-container aoc-mini-comic-space"></div>
                        <div id="aoc-mini-comics-romance" class="aoc-mini-comic-container aoc-mini-comic-space"></div>
                        <div id="aoc-mini-comics-scifi" class="aoc-mini-comic-container aoc-mini-comic-space"></div>
                        <div id="aoc-mini-comics-superhero" class="aoc-mini-comic-container aoc-mini-comic-space"></div>
                        <div id="aoc-mini-comics-western" class="aoc-mini-comic-container aoc-mini-comic-space"></div>
                    </div>
                    <div id="aoc-mini-ripoffs">
                        <div id="aoc-mini-ripoffs-crime" class="aoc-mini-comic-container aoc-mini-ripoff-space"></div>
                        <div id="aoc-mini-ripoffs-horror" class="aoc-mini-comic-container aoc-mini-ripoff-space"></div>
                        <div id="aoc-mini-ripoffs-romance" class="aoc-mini-comic-container aoc-mini-ripoff-space"></div>
                        <div id="aoc-mini-ripoffs-scifi" class="aoc-mini-comic-container aoc-mini-ripoff-space"></div>
                        <div id="aoc-mini-ripoffs-superhero" class="aoc-mini-comic-container aoc-mini-ripoff-space"></div>
                        <div id="aoc-mini-ripoffs-western" class="aoc-mini-comic-container aoc-mini-ripoff-space"></div>
                    </div>
                </div>
                <div id="aoc-extra-editors">
                    <div id="aoc-extra-editor-space-yellow" class="aoc-extra-editor-space"></div>
                    <div id="aoc-extra-editor-space-salmon" class="aoc-extra-editor-space"></div>
                    <div id="aoc-extra-editor-space-teal" class="aoc-extra-editor-space"></div>
                    <div id="aoc-extra-editor-space-brown" class="aoc-extra-editor-space"></div>
                </div>
                <div id="aoc-map">
                    <div id="aoc-map-order-spaces">
                        <div id="aoc-map-order-space-105" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-203" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-205" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-207" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-302" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-304" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-306" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-308" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-403" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-405" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-407" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-502" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-504" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-506" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-508" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-603" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-605" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-607" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-701" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-702" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-704" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-706" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-708" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-709" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-803" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-805" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-807" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-902" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-904" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-906" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-908" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1003" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1005" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1007" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1102" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1104" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1106" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1108" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1203" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1205" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1207" class="aoc-map-order-space"></div>
                        <div id="aoc-map-order-space-1305" class="aoc-map-order-space"></div>
                    </div>
                </div>
            </div>
            <div id="aoc-chart" class="aoc-board-section">
                <div id="aoc-chart-start" class="aoc-board-image aoc-chart-start"></div>
                <!-- BEGIN playerchart -->
                <div id="aoc-chart-{player_id}" class="aoc-board-image aoc-chart-{color}"></div>
                <!-- END playerchart -->
                <div id="aoc-chart-end" class="aoc-board-image aoc-chart-end"></div>
            </div>
            <div id="aoc-card-market"></div>
        </div>
        <div id="aoc-players-section">
            <!-- BEGIN playerarea -->
            <div id="aoc-player-mat-{player_id}" class="aoc-player-mat aoc-player-mat-{color}"></div>
            <!-- END playerarea -->
        </div>
    </div>
</div>

<div id="aoc-overall"></div>

{OVERALL_GAME_FOOTER}