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
                        <div id="aoc-mini-ripoffs-superhero" class="aoc-mini-comic-container aoc-mini-ripoff-space">
                        </div>
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
                    <div id="aoc-map-agent-spaces">
                        <div id="aoc-map-agent-space-0" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-101" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-102" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-103" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-104" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-201" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-202" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-203" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-204" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-301" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-302" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-303" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-304" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-401" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-402" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-403" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-404" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-501" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-502" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-503" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-504" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-601" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-602" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-603" class="aoc-map-agent-space"></div>
                        <div id="aoc-map-agent-space-604" class="aoc-map-agent-space"></div>
                    </div>
                    <div id="aoc-tickets-space"></div>
                </div>
            </div>
            <div id="aoc-chart" class="aoc-board-section" style="display: none;">
                <div id="aoc-chart-start" class="aoc-board-image aoc-chart-start"></div>
                <!-- BEGIN playerchart -->
                <div id="aoc-chart-{player_id}" class="aoc-board-image aoc-chart-{color}"></div>
                <!-- END playerchart -->
                <div id="aoc-chart-end" class="aoc-board-image aoc-chart-end"></div>
            </div>
            <div id="aoc-card-market">
                <div id="aoc-component-supply" class="aoc-card-market-row" style="display: none;">
                    <div id="aoc-ripoff-deck" class="aoc-deck"></div>
                    <div id="aoc-token-bank">
                        <div id="aoc-mastery-tokens"></div>
                        <div id="aoc-small-tokens">
                            <div id="aoc-idea-tokens">
                                <div id="aoc-idea-tokens-row-1" class="aoc-idea-tokens-row">
                                    <div id="aoc-crime-tokens" class="aoc-idea-bank">
                                        <div id="aoc-idea-crime-1" class="aoc-idea-token aoc-idea-token-crime"></div>
                                        <div id="aoc-idea-crime-2" class="aoc-idea-token aoc-idea-token-crime"></div>
                                        <div id="aoc-idea-crime-3" class="aoc-idea-token aoc-idea-token-crime"></div>
                                        <div id="aoc-idea-crime-4" class="aoc-idea-token aoc-idea-token-crime"></div>
                                        <div id="aoc-idea-crime-5" class="aoc-idea-token aoc-idea-token-crime"></div>
                                    </div>
                                    <div id="aoc-horror-tokens" class="aoc-idea-bank">
                                        <div id="aoc-idea-horror-1" class="aoc-idea-token aoc-idea-token-horror"></div>
                                        <div id="aoc-idea-horror-2" class="aoc-idea-token aoc-idea-token-horror"></div>
                                        <div id="aoc-idea-horror-3" class="aoc-idea-token aoc-idea-token-horror"></div>
                                        <div id="aoc-idea-horror-4" class="aoc-idea-token aoc-idea-token-horror"></div>
                                        <div id="aoc-idea-horror-5" class="aoc-idea-token aoc-idea-token-horror"></div>
                                    </div>
                                    <div id="aoc-romance-tokens" class="aoc-idea-bank">
                                        <div id="aoc-idea-romance-1" class="aoc-idea-token aoc-idea-token-romance">
                                        </div>
                                        <div id="aoc-idea-romance-2" class="aoc-idea-token aoc-idea-token-romance">
                                        </div>
                                        <div id="aoc-idea-romance-3" class="aoc-idea-token aoc-idea-token-romance">
                                        </div>
                                        <div id="aoc-idea-romance-4" class="aoc-idea-token aoc-idea-token-romance">
                                        </div>
                                        <div id="aoc-idea-romance-5" class="aoc-idea-token aoc-idea-token-romance">
                                        </div>
                                    </div>
                                </div>
                                <div id="aoc-idea-tokens-row-1" class="aoc-idea-tokens-row">
                                    <div id="aoc-scifi-tokens" class="aoc-idea-bank">
                                        <div id="aoc-idea-scifi-1" class="aoc-idea-token aoc-idea-token-scifi"></div>
                                        <div id="aoc-idea-scifi-2" class="aoc-idea-token aoc-idea-token-scifi"></div>
                                        <div id="aoc-idea-scifi-3" class="aoc-idea-token aoc-idea-token-scifi"></div>
                                        <div id="aoc-idea-scifi-4" class="aoc-idea-token aoc-idea-token-scifi"></div>
                                        <div id="aoc-idea-scifi-5" class="aoc-idea-token aoc-idea-token-scifi"></div>
                                    </div>
                                    <div id="aoc-superhero-tokens" class="aoc-idea-bank">
                                        <div id="aoc-idea-superhero-1" class="aoc-idea-token aoc-idea-token-superhero">
                                        </div>
                                        <div id="aoc-idea-superhero-2" class="aoc-idea-token aoc-idea-token-superhero">
                                        </div>
                                        <div id="aoc-idea-superhero-3" class="aoc-idea-token aoc-idea-token-superhero">
                                        </div>
                                        <div id="aoc-idea-superhero-4" class="aoc-idea-token aoc-idea-token-superhero">
                                        </div>
                                        <div id="aoc-idea-superhero-5" class="aoc-idea-token aoc-idea-token-superhero">
                                        </div>
                                    </div>
                                    <div id="aoc-western-tokens" class="aoc-idea-bank">
                                        <div id="aoc-idea-western-1" class="aoc-idea-token aoc-idea-token-western">
                                        </div>
                                        <div id="aoc-idea-western-2" class="aoc-idea-token aoc-idea-token-western">
                                        </div>
                                        <div id="aoc-idea-western-3" class="aoc-idea-token aoc-idea-token-western">
                                        </div>
                                        <div id="aoc-idea-western-4" class="aoc-idea-token aoc-idea-token-western">
                                        </div>
                                        <div id="aoc-idea-western-5" class="aoc-idea-token aoc-idea-token-western">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="aoc-other-tokens">
                                <div id="aoc-other-tokens-row-1" class="aoc-other-tokens-row">
                                    <div id="aoc-increase-writer-tokens" class="aoc-token-bank">
                                        <div id="aoc-increase-writer-1"
                                            class="aoc-increase-token aoc-token-increase-writer-2"></div>
                                        <div id="aoc-increase-writer-2"
                                            class="aoc-increase-token aoc-token-increase-writer-2"></div>
                                        <div id="aoc-increase-writer-3"
                                            class="aoc-increase-token aoc-token-increase-writer-2"></div>
                                        <div id="aoc-increase-writer-4"
                                            class="aoc-increase-token aoc-token-increase-writer-2"></div>
                                        <div id="aoc-increase-writer-5"
                                            class="aoc-increase-token aoc-token-increase-writer-2"></div>
                                    </div>
                                    <div id="aoc-increase-artist-tokens" class="aoc-token-bank">
                                        <div id="aoc-increase-artist-1"
                                            class="aoc-increase-token aoc-token-increase-artist-2"></div>
                                        <div id="aoc-increase-artist-2"
                                            class="aoc-increase-token aoc-token-increase-artist-2"></div>
                                        <div id="aoc-increase-artist-3"
                                            class="aoc-increase-token aoc-token-increase-artist-2"></div>
                                        <div id="aoc-increase-artist-4"
                                            class="aoc-increase-token aoc-token-increase-artist-2"></div>
                                        <div id="aoc-increase-artist-5"
                                            class="aoc-increase-token aoc-token-increase-artist-2"></div>
                                    </div>
                                    <div id="aoc-better-color-tokens" class="aoc-token-bank">
                                        <div id="aoc-better-color-1" class="aoc-token-better-color"></div>
                                        <div id="aoc-better-color-2" class="aoc-token-better-color"></div>
                                        <div id="aoc-better-color-3" class="aoc-token-better-color"></div>
                                        <div id="aoc-better-color-4" class="aoc-token-better-color"></div>
                                        <div id="aoc-better-color-5" class="aoc-token-better-color"></div>
                                    </div>
                                </div>
                                <div id="aoc-other-tokens-row-2" class="aoc-other-tokens-row">
                                    <div id="aoc-coin-tokens" class="aoc-token-bank">
                                        <div id="aoc-coin-1" class="aoc-round-token aoc-token-coin"></div>
                                        <div id="aoc-coin-2" class="aoc-round-token aoc-token-coin"></div>
                                        <div id="aoc-coin-3" class="aoc-round-token aoc-token-coin"></div>
                                        <div id="aoc-coin-4" class="aoc-round-token aoc-token-coin"></div>
                                        <div id="aoc-coin-5" class="aoc-round-token aoc-token-coin"></div>
                                    </div>
                                    <div id="aoc-point-tokens" class="aoc-token-bank">
                                        <div id="aoc-point-1" class="aoc-round-token aoc-token-point"></div>
                                        <div id="aoc-point-2" class="aoc-round-token aoc-token-point"></div>
                                        <div id="aoc-point-3" class="aoc-round-token aoc-token-point"></div>
                                        <div id="aoc-point-4" class="aoc-round-token aoc-token-point"></div>
                                        <div id="aoc-point-5" class="aoc-round-token aoc-token-point"></div>
                                    </div>
                                    <div id="aoc-hype-tokens" class="aoc-token-bank">
                                        <div id="aoc-hype-1" class="aoc-round-token aoc-token-hype"></div>
                                        <div id="aoc-hype-2" class="aoc-round-token aoc-token-hype"></div>
                                        <div id="aoc-hype-3" class="aoc-round-token aoc-token-hype"></div>
                                        <div id="aoc-hype-4" class="aoc-round-token aoc-token-hype"></div>
                                        <div id="aoc-hype-5" class="aoc-round-token aoc-token-hype"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="aoc-writers-market" class="aoc-card-row-container aoc-card-market-row">
                    <div id="aoc-writer-deck" class="aoc-deck">
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-facedown-2"></div>
                    </div>
                    <div id="aoc-writers-available" class="aoc-card-market-row">
                        <div id="test" class="aoc-creative-card aoc-writer-horror-21"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-western-30"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-romance-10"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-horror-20"></div>
                    </div>
                    <div id="aoc-writers-discard" class="aoc-discard" style="display: none;">
                        <div id="test" class="aoc-creative-card aoc-writer-superhero-10"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-horror-21"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-western-30"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-romance-10"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-horror-20"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-superhero-10"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-horror-21"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-western-30"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-romance-10"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-horror-20"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-superhero-10"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-horror-21"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-western-30"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-romance-10"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-horror-20"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-superhero-10"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-horror-21"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-western-30"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-romance-10"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-horror-20"></div>
                        <div id="test" class="aoc-creative-card aoc-writer-romance-30"></div>
                        <div id="aoc-writers-discard-cover" class="aoc-discard-cover">
                            <div id="aoc-writers-trash" class="aoc-trash-icon"></div>
                        </div>
                    </div>
                </div>
                <div id="aoc-artists-market" class="aoc-card-row-container aoc-card-market-row">
                    <div id="aoc-artist-deck" class="aoc-deck">
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-facedown-2"></div>
                    </div>
                    <div id="aoc-artists-available" class="aoc-card-market-row">
                        <div id="test" class="aoc-creative-card aoc-artist-horror-21"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-western-30"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-romance-10"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-horror-20"></div>
                    </div>
                    <div id="aoc-artists-discard" class="aoc-discard" style="display: none;">
                        <div id="test" class="aoc-creative-card aoc-artist-superhero-10"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-horror-21"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-western-30"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-romance-10"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-horror-20"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-superhero-10"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-horror-21"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-western-30"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-romance-10"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-horror-20"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-superhero-10"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-horror-21"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-western-30"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-romance-10"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-horror-20"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-superhero-10"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-horror-21"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-western-30"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-romance-10"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-horror-20"></div>
                        <div id="test" class="aoc-creative-card aoc-artist-romance-30"></div>
                        <div id="aoc-artists-discard-cover" class="aoc-discard-cover">
                            <div id="aoc-artists-trash" class="aoc-trash-icon"></div>
                        </div>
                    </div>
                </div>
                <div id="aoc-comics-market" class="aoc-card-row-container aoc-card-market-row">
                    <div id="aoc-comic-deck" class="aoc-deck">
                        <div id="test" class="aoc-comic-card aoc-comic-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                        <div id="test" class="aoc-comic-card aoc-horror-facedown"></div>
                    </div>
                    <div id="aoc-comics-available" class="aoc-card-market-row">
                        <div id="test" class="aoc-comic-card aoc-comic-horror-2"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-western-3"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-romance-1"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-horror-4"></div>
                    </div>
                    <div id="aoc-comics-discard" class="aoc-discard" style="display: none;">
                        <div id="test" class="aoc-comic-card aoc-comic-superhero-1"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-horror-2"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-western-3"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-romance-1"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-horror-2"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-superhero-1"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-horror-2"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-western-3"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-romance-1"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-horror-2"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-superhero-1"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-horror-2"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-western-3"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-romance-1"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-horror-2"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-superhero-1"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-horror-2"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-western-3"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-romance-1"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-horror-2"></div>
                        <div id="test" class="aoc-comic-card aoc-comic-romance-3"></div>
                        <div id="aoc-comics-discard-cover" class="aoc-discard-cover">
                            <div id="aoc-comics-trash" class="aoc-trash-icon"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="aoc-players-section">
            <!-- BEGIN playerarea -->
            <div id="aoc-player-background-panel-{player_id}" class="aoc-player-background-panel">
                <div id="aoc-player-area-{player_id}" class="aoc-player-area aoc-player-area-{color}">
                    <div id="aoc-player-area-left-{player_id}" class="aoc-player-area-left">
                        <div id="aoc-paper-supply-{player_id}" class="aoc-paper-supply">
                            <div id="aoc-editor-container-{player_id}" class="aoc-editor-container"></div>
                            <span id="aoc-player-name-{player_id}" class="aoc-player-name aoc-player-name-{color}">
                                {player_name}
                            </span>
                            <div id="aoc-player-crime-supply-{player_id}" class="aoc-player-supply aoc-empty">
                                <span id="aoc-player-crime-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squanda">0</span>
                                <span id="aoc-player-crime-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-crime"></span>
                            </div>
                            <div id="aoc-player-horror-supply-{player_id}" class="aoc-player-supply aoc-empty">
                                <span id="aoc-player-horror-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squanda">0</span>
                                <span id="aoc-player-horror-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-horror"></span>
                            </div>
                            <div id="aoc-player-romance-supply-{player_id}" class="aoc-player-supply aoc-empty">
                                <span id="aoc-player-romance-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squanda">0</span>
                                <span id="aoc-player-romance-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-romance"></span>
                            </div>
                            <div id="aoc-player-scifi-supply-{player_id}" class="aoc-player-supply aoc-empty">
                                <span id="aoc-player-scifi-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squanda">0</span>
                                <span id="aoc-player-scifi-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-scifi"></span>
                            </div>
                            <div id="aoc-player-superhero-supply-{player_id}" class="aoc-player-supply aoc-empty">
                                <span id="aoc-player-superhero-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squanda">0</span>
                                <span id="aoc-player-superhero-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-superhero"></span>
                            </div>
                            <div id="aoc-player-western-supply-{player_id}" class="aoc-player-supply aoc-empty">
                                <span id="aoc-player-western-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squanda">0</span>
                                <span id="aoc-player-western-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-western"></span>
                            </div>
                            <div id="aoc-player-money-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-money-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squanda">5</span>
                                <span id="aoc-player-money-{player_id}" class="aoc-round-token aoc-token-coin"></span>
                            </div>
                            <div id="aoc-player-point-supply-{player_id}" class="aoc-player-supply aoc-empty">
                                <span id="aoc-player-point-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squanda">0</span>
                                <span id="aoc-player-points-{player_id}" class="aoc-round-token aoc-token-point"></span>
                            </div>
                            <div id="aoc-player-income-{player_id}" class="aoc-player-supply aoc-empty">
                                <span id="aoc-player-income-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squanda">0</span>
                                <i id="aoc-player-income-{player_id}"
                                    class="aoc-icon-size fa6 fa6-solid fa6-money-bill-trend-up"></i>
                            </div>
                        </div>
                        <div id="aoc-player-mat-{player_id}" class="aoc-player-mat aoc-player-mat-{color}">
                            <div id="aoc-cube-one-space-{player_id}" class="aoc-cube-space aoc-cube-space-one"></div>
                            <div id="aoc-cube-two-space-{player_id}" class="aoc-cube-space aoc-cube-space-two"></div>
                            <div id="aoc-cube-three-space-{player_id}" class="aoc-cube-space aoc-cube-space-three"></div>
                        </div>
                    </div>
                    <div id="aoc-player-area-right-{player_id}" class="aoc-player-area-right">
                        <div id="aoc-player-mastery-{player_id}" class="aoc-player-mastery-container">
                            <div id="aoc-mastery-token-1" class="aoc-mastery-token aoc-mastery-token-crime"></div>
                            <div id="aoc-mastery-token-2" class="aoc-mastery-token aoc-mastery-token-horror"></div>
                            <div id="aoc-mastery-token-3" class="aoc-mastery-token aoc-mastery-token-romance"></div>
                            <div id="aoc-mastery-token-4" class="aoc-mastery-token aoc-mastery-token-scifi"></div>
                            <div id="aoc-mastery-token-5" class="aoc-mastery-token aoc-mastery-token-superhero"></div>
                            <div id="aoc-mastery-token-6" class="aoc-mastery-token aoc-mastery-token-western"></div>
                        </div>
                        <div id="aoc-player-tokens-{player_id}" class="aoc-player-token-container">
                            <div id="aoc-ticket-1" class="aoc-ticket"></div>
                            <div id="aoc-salesorder-11" class="aoc-salesorder aoc-salesorder-horror-facedown"></div>
                            <div id="aoc-salesorder-11" class="aoc-salesorder aoc-salesorder-horror-facedown"></div>
                            <div id="aoc-salesorder-11" class="aoc-salesorder aoc-salesorder-horror-facedown"></div>
                        </div>
                        <div id="aoc-hand-{player_id}" class="aoc-hand"></div>
                    </div>
                </div>
            </div>
            <!-- END playerarea -->
        </div>
    </div>
</div>

<div id="aoc-overall"></div>

{OVERALL_GAME_FOOTER}