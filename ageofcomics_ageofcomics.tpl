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
                <div id="aoc-calendar-slot-10" class="aoc-calendar-slot"></div>
                <div id="aoc-calendar-slot-20" class="aoc-calendar-slot"></div>
                <div id="aoc-calendar-slot-31" class="aoc-calendar-slot"></div>
                <div id="aoc-calendar-slot-32" class="aoc-calendar-slot"></div>
                <div id="aoc-calendar-slot-40" class="aoc-calendar-slot"></div>
                <div id="aoc-calendar-slot-50" class="aoc-calendar-slot"></div>
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


<script type=" text/javascript">
    // Javascript HTML templates

    /*
    // Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}">
    <
    /div>';

    *
    /
</script>

{OVERALL_GAME_FOOTER}