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
-->


<div id="aoc-gboard">
    <div id="aoc-layout">
        <div id="aoc-artists-discard-popup" class="aoc-hidden aoc-discard-popup whiteblock">
            <a id="aoc-artists-discard-close" class="aoc-discard-popup-close" href="#">
                <i class="aoc-icon-size fa6 fa6-solid fa6-square-xmark fa6-2x aria-hidden=" true"></i>
            </a>
            <div id="aoc-artists-discard-popup-header">
                <div id="aoc-artists-discard-popup-header-text" class="aoc-header-text aoc-squada">{ARTIST_DISCARDS}
                </div>
            </div>
            <div id="aoc-discarded-artist-cards" class="aoc-discarded-cards"></div>
        </div>
        <div id="aoc-writers-discard-popup" class="aoc-hidden aoc-discard-popup whiteblock">
            <a id="aoc-writers-discard-close" class="aoc-discard-popup-close" href="#">
                <i class="aoc-icon-size fa6 fa6-solid fa6-square-xmark fa6-2x aria-hidden=" true"></i>
            </a>
            <div id="aoc-writers-discard-popup-header">
                <div id="aoc-writers-discard-popup-header-text" class="aoc-header-text aoc-squada">{WRITER_DISCARDS}
                </div>
            </div>
            <div id="aoc-discarded-writer-cards" class="aoc-discarded-cards"></div>
        </div>
        <div id="aoc-comics-discard-popup" class="aoc-hidden aoc-discard-popup whiteblock">
            <a id="aoc-comics-discard-close" class="aoc-discard-popup-close" href="#">
                <i class="aoc-icon-size fa6 fa6-solid fa6-square-xmark fa6-2x aria-hidden=" true"></i>
            </a>
            <div id="aoc-comics-discard-popup-header">
                <div id="aoc-comics-discard-popup-header-text" class="aoc-header-text aoc-squada">{COMIC_DISCARDS}
                </div>
            </div>
            <div id="aoc-discarded-comic-cards" class="aoc-discarded-cards"></div>
        </div>
        <div id="aoc-print-menu" class="aoc-hidden whiteblock">
            <div id="aoc-print-comics-header">
                <div id="aoc-print-comics-header-text" class="aoc-header-text aoc-squada">{SELECT_COMIC_TO_PRINT}
                </div>
            </div>
            <div id="aoc-print-comics-menu" class="aoc-print-menu"></div>
            <div id="aoc-print-writers-header">
                <div id="aoc-print-writers-header-text" class="aoc-header-text aoc-squada">{SELECT_WRITER}</div>
            </div>
            <div id="aoc-print-writers-menu" class="aoc-print-menu"></div>
            <div id="aoc-print-artists-header">
                <div id="aoc-print-artists-header-text" class="aoc-header-text aoc-squada">{SELECT_ARTIST}</div>
            </div>
            <div id="aoc-print-artists-menu" class="aoc-print-menu"></div>
        </div>
        <div id="aoc-improve-creatives-menu" class="aoc-hidden whiteblock">
            <div id="aoc-improve-creatives-button-container"></div>
            <div id="aoc-improve-creatives-header">
                <div id="aoc-improve-creatives-header-text" class="aoc-header-text aoc-squada">{IMPROVE_CREATIVES}
                </div>
            </div>
            <div id="aoc-improve-creatives-comics" class="aoc-increase-menu"></div>
        </div>
        <div id="aoc-select-comic-for-order-menu" class="aoc-hidden whiteblock">
            <div id="aoc-select-comic-for-order-header">
                <div id="aoc-select-comic-for-order-header-text" class="aoc-header-text aoc-squada">
                    {SELECT_COMIC_FOR_ORDER}
                </div>
            </div>
            <div id="aoc-select-comics" class="aoc-increase-menu"></div>
        </div>
        <div id="aoc-select-start-items" class="whiteblock" style="display: none;">
            <div id="aoc-select-start-items-header">
                <div id="aoc-select-start-items-header-text" class="aoc-squada">{SELECT_START_ITEMS}</div>
            </div>
            <div id="aoc-select-start-comic-buttons">
                <div id="aoc-select-start-comic-genre">
                    <div id="aoc-select-starting-comic-crime" class="aoc-comic-card aoc-comic-crime-facedown"></div>
                    <div id="aoc-select-starting-comic-horror" class="aoc-comic-card aoc-comic-horror-facedown">
                    </div>
                    <div id="aoc-select-starting-comic-romance" class="aoc-comic-card aoc-comic-romance-facedown">
                    </div>
                    <div id="aoc-select-starting-comic-scifi" class="aoc-comic-card aoc-comic-scifi-facedown"></div>
                    <div id="aoc-select-starting-comic-superhero" class="aoc-comic-card aoc-comic-superhero-facedown">
                    </div>
                    <div id="aoc-select-starting-comic-western" class="aoc-comic-card aoc-comic-western-facedown">
                    </div>
                </div>
                <div id="aoc-select-start-ideas">
                    <div id="aoc-select-start-idea-buttons">
                        <div id="aoc-select-starting-idea-crime" class="aoc-idea-token aoc-idea-token-crime">
                        </div>
                        <div id="aoc-select-starting-idea-horror" class="aoc-idea-token aoc-idea-token-horror">
                        </div>
                        <div id="aoc-select-starting-idea-romance" class="aoc-idea-token aoc-idea-token-romance">
                        </div>
                        <div id="aoc-select-starting-idea-scifi" class="aoc-idea-token aoc-idea-token-scifi"></div>
                        <div id="aoc-select-starting-idea-superhero" class="aoc-idea-token aoc-idea-token-superhero">
                        </div>
                        <div id="aoc-select-starting-idea-western" class="aoc-idea-token aoc-idea-token-western">
                        </div>
                    </div>
                    <div id="aoc-select-start-idea-containers" class="aoc-select-containers"></div>
                </div>
            </div>
        </div>
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
                <div id="aoc-action-hire">
                    <div id="aoc-action-hire-spaces">
                        <div id="aoc-action-space-hire-1" class="aoc-action-space" space=" 10001"></div>
                        <div id="aoc-action-space-hire-2" class="aoc-action-space" space="10002"></div>
                        <div id="aoc-action-space-hire-3" class="aoc-action-space" space="10003"></div>
                        <div id="aoc-action-space-hire-4" class="aoc-action-space" space="10004"></div>
                        <div id="aoc-action-space-hire-5" class="aoc-action-space" space="10005"></div>
                    </div>
                    <div id="aoc-action-hire-upgrade-spaces">
                        <div id="aoc-action-upgrade-hire-yellow" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-hire-salmon" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-hire-teal" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-hire-brown" class="aoc-upgrade-space"></div>
                    </div>
                </div>
                <div id="aoc-action-develop">
                    <div id="aoc-action-develop-spaces">
                        <div id="aoc-action-space-develop-1" class="aoc-action-space" space="20001"></div>
                        <div id="aoc-action-space-develop-2" class="aoc-action-space" space="20002"></div>
                        <div id="aoc-action-space-develop-3" class="aoc-action-space" space="20003"></div>
                        <div id="aoc-action-space-develop-4" class="aoc-action-space" space="20004"></div>
                        <div id="aoc-action-space-develop-5" class="aoc-action-space" space="20005"></div>
                    </div>
                    <div id="aoc-action-develop-upgrade-spaces">
                        <div id="aoc-action-upgrade-develop-yellow" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-develop-salmon" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-develop-teal" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-develop-brown" class="aoc-upgrade-space"></div>
                    </div>
                </div>
                <div id="aoc-action-ideas">
                    <div id="aoc-action-ideas-spaces">
                        <div id="aoc-action-space-ideas-1" class="aoc-action-space" space="30001"></div>
                        <div id="aoc-action-space-ideas-2" class="aoc-action-space" space="30002"></div>
                        <div id="aoc-action-space-ideas-3" class="aoc-action-space" space="30003"></div>
                        <div id="aoc-action-space-ideas-4" class="aoc-action-space" space="30004"></div>
                        <div id="aoc-action-space-ideas-5" class="aoc-action-space" space="30005"></div>
                    </div>
                    <div id="aoc-action-ideas-idea-spaces">
                        <div id="aoc-action-ideas-crime" class="aoc-action-ideas-idea-space"></div>
                        <div id="aoc-action-ideas-scifi" class="aoc-action-ideas-idea-space"></div>
                        <div id="aoc-action-ideas-superhero" class="aoc-action-ideas-idea-space"></div>
                        <div id="aoc-action-ideas-romance" class="aoc-action-ideas-idea-space"></div>
                        <div id="aoc-action-ideas-western" class="aoc-action-ideas-idea-space"></div>
                        <div id="aoc-action-ideas-horror" class="aoc-action-ideas-idea-space"></div>
                    </div>
                    <div id="aoc-action-ideas-upgrade-spaces">
                        <div id="aoc-action-upgrade-ideas-yellow" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-ideas-salmon" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-ideas-teal" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-ideas-brown" class="aoc-upgrade-space"></div>
                    </div>
                </div>
                <div id="aoc-action-print">
                    <div id="aoc-action-print-spaces">
                        <div id="aoc-action-space-print-1" class="aoc-action-space" space="40001"></div>
                        <div id="aoc-action-space-print-2" class="aoc-action-space" space="40002"></div>
                        <div id="aoc-action-space-print-3" class="aoc-action-space" space="40003"></div>
                        <div id="aoc-action-space-print-4" class="aoc-action-space" space="40004"></div>
                        <div id="aoc-action-space-print-5" class="aoc-action-space" space="40005"></div>
                    </div>
                    <div id="aoc-action-print-upgrade-spaces">
                        <div id="aoc-action-upgrade-print-yellow" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-print-salmon" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-print-teal" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-print-brown" class="aoc-upgrade-space"></div>
                    </div>
                </div>
                <div id="aoc-action-royalties">
                    <div id="aoc-action-royalties-spaces">
                        <div id="aoc-action-space-royalties-1" class="aoc-action-space" space="50001"></div>
                        <div id="aoc-action-space-royalties-2" class="aoc-action-space" space="50002"></div>
                        <div id="aoc-action-space-royalties-3" class="aoc-action-space" space="50003"></div>
                        <div id="aoc-action-space-royalties-4" class="aoc-action-space" space="50004"></div>
                        <div id="aoc-action-space-royalties-5" class="aoc-action-space" space="50005"></div>
                    </div>
                    <div id="aoc-action-royalties-upgrade-spaces">
                        <div id="aoc-action-upgrade-royalties-yellow" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-royalties-salmon" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-royalties-teal" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-royalties-brown" class="aoc-upgrade-space"></div>
                    </div>
                </div>
                <div id="aoc-action-sales">
                    <div id="aoc-action-sales-spaces">
                        <div id="aoc-action-space-sales-1" class="aoc-action-space" space="60001"></div>
                        <div id="aoc-action-space-sales-2" class="aoc-action-space" space="60002"></div>
                        <div id="aoc-action-space-sales-3" class="aoc-action-space" space="60003"></div>
                        <div id="aoc-action-space-sales-4" class="aoc-action-space" space="60004"></div>
                        <div id="aoc-action-space-sales-5" class="aoc-action-space" space="60005"></div>
                    </div>
                    <div id="aoc-action-sales-upgrade-spaces">
                        <div id="aoc-action-upgrade-sales-yellow" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-sales-salmon" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-sales-teal" class="aoc-upgrade-space"></div>
                        <div id="aoc-action-upgrade-sales-brown" class="aoc-upgrade-space"></div>
                    </div>
                </div>
                <div id="aoc-mini-comics-section">
                    <div id="aoc-mini-comics">
                        <div id="aoc-mini-comics-crime" class="aoc-mini-comic-container aoc-mini-comic-space">
                        </div>
                        <div id="aoc-mini-comics-horror" class="aoc-mini-comic-container aoc-mini-comic-space">
                        </div>
                        <div id="aoc-mini-comics-romance" class="aoc-mini-comic-container aoc-mini-comic-space">
                        </div>
                        <div id="aoc-mini-comics-scifi" class="aoc-mini-comic-container aoc-mini-comic-space">
                        </div>
                        <div id="aoc-mini-comics-superhero" class="aoc-mini-comic-container aoc-mini-comic-space">
                        </div>
                        <div id="aoc-mini-comics-western" class="aoc-mini-comic-container aoc-mini-comic-space">
                        </div>
                    </div>
                    <div id="aoc-mini-ripoffs">
                        <div id="aoc-mini-ripoffs-crime" class="aoc-mini-comic-container aoc-mini-ripoff-space">
                        </div>
                        <div id="aoc-mini-ripoffs-horror" class="aoc-mini-comic-container aoc-mini-ripoff-space">
                        </div>
                        <div id="aoc-mini-ripoffs-romance" class="aoc-mini-comic-container aoc-mini-ripoff-space">
                        </div>
                        <div id="aoc-mini-ripoffs-scifi" class="aoc-mini-comic-container aoc-mini-ripoff-space">
                        </div>
                        <div id="aoc-mini-ripoffs-superhero" class="aoc-mini-comic-container aoc-mini-ripoff-space">
                        </div>
                        <div id="aoc-mini-ripoffs-western" class="aoc-mini-comic-container aoc-mini-ripoff-space">
                        </div>
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
            <div id="aoc-card-market">
                <div id="aoc-writers-market" class="aoc-card-row-container aoc-card-market-row">
                    <div id="aoc-writer-deck" class="aoc-deck"></div>
                    <div id="aoc-writers-available" class="aoc-card-market-row"></div>
                    <div id="aoc-writers-discard" class="aoc-discard">
                        <div id="aoc-writers-discard-cover" class="aoc-discard-cover">
                            <div id="aoc-writers-trash" class="aoc-trash-icon"></div>
                        </div>
                    </div>
                </div>
                <div id="aoc-artists-market" class="aoc-card-row-container aoc-card-market-row">
                    <div id="aoc-artist-deck" class="aoc-deck"></div>
                    <div id="aoc-artists-available" class="aoc-card-market-row"></div>
                    <div id="aoc-artists-discard" class="aoc-discard">
                        <div id="aoc-artists-discard-cover" class="aoc-discard-cover">
                            <div id="aoc-artists-trash" class="aoc-trash-icon"></div>
                        </div>
                    </div>
                </div>
                <div id="aoc-comics-market" class="aoc-card-row-container aoc-card-market-row">
                    <div id="aoc-comic-deck" class="aoc-deck"></div>
                    <div id="aoc-comics-available" class="aoc-card-market-row"></div>
                    <div id="aoc-comics-discard" class="aoc-discard">
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
                            <div id="aoc-player-name-{player_id}" class="aoc-player-name aoc-player-name-{color}">
                                <i class="aoc-icon-size fa6 fa6-solid fa6-circle-left aoc-arrow aoc-hidden"></i>
                                <span class="aoc-squada">{player_name}</span>
                                <i class="aoc-icon-size fa6 fa6-solid fa6-circle-right aoc-arrow aoc-hidden"></i>
                            </div>
                            <div id="aoc-player-crime-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-crime-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <span id="aoc-player-crime-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-crime"></span>
                            </div>
                            <div id="aoc-player-horror-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-horror-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <span id="aoc-player-horror-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-horror"></span>
                            </div>
                            <div id="aoc-player-romance-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-romance-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <span id="aoc-player-romance-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-romance"></span>
                            </div>
                            <div id="aoc-player-scifi-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-scifi-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <span id="aoc-player-scifi-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-scifi"></span>
                            </div>
                            <div id="aoc-player-superhero-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-superhero-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <span id="aoc-player-superhero-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-superhero"></span>
                            </div>
                            <div id="aoc-player-western-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-western-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <span id="aoc-player-western-{player_id}"
                                    class="aoc-idea-token aoc-idea-token-western"></span>
                            </div>
                            <div id="aoc-player-money-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-money-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <span id="aoc-player-money-{player_id}" class="aoc-round-token aoc-token-coin"></span>
                            </div>
                            <div id="aoc-player-point-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-point-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <span id="aoc-player-points-{player_id}" class="aoc-round-token aoc-token-point"></span>
                            </div>
                            <div id="aoc-player-income-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-income-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <i id="aoc-player-income-{player_id}"
                                    class="aoc-icon-size fa6 fa6-solid fa6-money-bill-trend-up"></i>
                            </div>
                            <div id="aoc-player-hand-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-hand-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <i id="aoc-player-hand-{player_id}" class="aoc-icon-size aoc-hand-icon"></i>
                            </div>
                            <div id="aoc-player-ticket-supply-{player_id}" class="aoc-player-supply">
                                <span id="aoc-player-ticket-count-{player_id}"
                                    class="aoc-player-supply-count aoc-squada"></span>
                                <i id="aoc-player-ticket-{player_id}" class="aoc-icon-size aoc-ticket-icon"></i>
                            </div>
                            <div id="aoc-token-container-{player_id}" class="aoc-token-container">
                                <div id="aoc-mastery-container-{player_id}" class="aoc-mastery-container"></div>
                                <div id="aoc-sales-order-container-{player_id}" class="aoc-sales-order-container">
                                </div>
                            </div>
                        </div>
                        <div id="aoc-player-mat-{player_id}" class="aoc-player-mat aoc-player-mat-{color}">
                            <div id="aoc-writer-slot-1-{player_id}" class="aoc-card-slot aoc-writer-slot-one">
                            </div>
                            <div id="aoc-writer-slot-2-{player_id}" class="aoc-card-slot aoc-writer-slot-two">
                            </div>
                            <div id="aoc-writer-slot-3-{player_id}" class="aoc-card-slot aoc-writer-slot-three">
                            </div>
                            <div id="aoc-writer-slot-4-{player_id}" class="aoc-card-slot aoc-writer-slot-four">
                            </div>
                            <div id="aoc-writer-slot-5-{player_id}" class="aoc-card-slot aoc-writer-slot-five">
                            </div>
                            <div id="aoc-writer-slot-6-{player_id}" class="aoc-card-slot aoc-writer-slot-six">
                            </div>
                            <div id="aoc-artist-slot-1-{player_id}" class="aoc-card-slot aoc-artist-slot-one">
                            </div>
                            <div id="aoc-artist-slot-2-{player_id}" class="aoc-card-slot aoc-artist-slot-two">
                            </div>
                            <div id="aoc-artist-slot-3-{player_id}" class="aoc-card-slot aoc-artist-slot-three">
                            </div>
                            <div id="aoc-artist-slot-4-{player_id}" class="aoc-card-slot aoc-artist-slot-four">
                            </div>
                            <div id="aoc-artist-slot-5-{player_id}" class="aoc-card-slot aoc-artist-slot-five">
                            </div>
                            <div id="aoc-artist-slot-6-{player_id}" class="aoc-card-slot aoc-artist-slot-six">
                            </div>
                            <div id="aoc-comic-slot-1-{player_id}" class="aoc-card-slot aoc-comic-slot-one">
                            </div>
                            <div id="aoc-comic-slot-2-{player_id}" class="aoc-card-slot aoc-comic-slot-two">
                            </div>
                            <div id="aoc-comic-slot-3-{player_id}" class="aoc-card-slot aoc-comic-slot-three">
                            </div>
                            <div id="aoc-comic-slot-4-{player_id}" class="aoc-card-slot aoc-comic-slot-four">
                            </div>
                            <div id="aoc-comic-slot-5-{player_id}" class="aoc-card-slot aoc-comic-slot-five">
                            </div>
                            <div id="aoc-comic-slot-6-{player_id}" class="aoc-card-slot aoc-comic-slot-six">
                            </div>
                            <div id="aoc-cube-one-space-{player_id}" class="aoc-cube-space aoc-cube-space-one">
                            </div>
                            <div id="aoc-cube-two-space-{player_id}" class="aoc-cube-space aoc-cube-space-two">
                            </div>
                            <div id="aoc-cube-three-space-{player_id}" class="aoc-cube-space aoc-cube-space-three">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END playerarea -->
        </div>
    </div>
</div>

<div id="aoc-player-hands">
    <!-- BEGIN playerhands -->
    <div id="aoc-floating-hand-wrapper-{player_id}" class="aoc-floating-hand-wrapper aoc-box-shadow-{color} {hidden}">
        <div id="aoc-hand-{player_id}" class="aoc-floating-hand">
        </div>
    </div>
    <!-- END playerhands -->
</div>

<div id="aoc-overall"></div>

{OVERALL_GAME_FOOTER}