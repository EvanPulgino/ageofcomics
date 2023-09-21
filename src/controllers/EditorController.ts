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

class EditorController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  setupEditors(editors: any): void {
    for (var key in editors) {
      this.createEditor(editors[key]);
    }
  }

  createEditor(editor: any): void {
    var editorDiv =
      '<div id="aoc-editor-' +
      editor.id +
      '" class="aoc-editor ' +
      editor.cssClass +
      '"></div>';
    if (editor.locationId == globalThis.LOCATION_EXTRA_EDITOR) {
      var color = this.ui.getPlayerColorAsString(editor.color);
      this.ui.createHtml(editorDiv, "aoc-extra-editor-space-" + color);
    } else if (editor.locationId == globalThis.LOCATION_PLAYER_AREA) {
      var color = this.ui.getPlayerColorAsString(editor.color);
      this.ui.createHtml(editorDiv, "aoc-editor-container-" + editor.playerId);
    } else {
      const actionSpaceDiv = dojo.query(
        "[space$=" + editor.locationId + "]"
      )[0];
      this.ui.createHtml(editorDiv, actionSpaceDiv.id);
    }
  }

  moveEditorToActionSpace(editor: any, actionSpace: any): void {
    const editorDiv = dojo.byId("aoc-editor-" + editor.id);
    const actionSpaceDiv = dojo.query("[space$=" + actionSpace + "]")[0];
    var animation = gameui.slideToObject(editorDiv, actionSpaceDiv);
    dojo.connect(animation, "onEnd", () => {
      gameui.attachToNewParent(editorDiv, actionSpaceDiv);
    });
    animation.play();
  }
}
