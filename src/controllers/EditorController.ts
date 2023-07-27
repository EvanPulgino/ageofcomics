/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * EditorController.ts
 *
 */

class EditorController extends GameBasics {

    setupEditors(editors: any) : void {
        for (var key in editors) {
            this.createEditor(editors[key]);
        }
    }

    createEditor(editor: any) : void {
        this.debug("creating editor", editor);
        var editorDiv = '<div id="aoc-editor-' + editor.id + '" class="aoc-editor ' + editor.cssClass + '"></div>';
        if (editor.locationId == globalThis.LOCATION_EXTRA_EDITOR) {
            console.log("creating extra editor");
            var color = this.getPlayerColorAsString(editor.color);
            this.createHtml(editorDiv,"aoc-extra-editor-space-" + color);
        }
        // TODO: Handle other editor locations
    }
}