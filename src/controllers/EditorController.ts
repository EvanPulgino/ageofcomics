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
 * Handles all front end interactions with the editors
 *
 */

class EditorController {
  ui: any;

  constructor(ui: any) {
    this.ui = ui;
  }

  /**
   * Setup all editors
   *
   * @param editors - the editors to setup
   */
  setupEditors(editors: any): void {
    for (var key in editors) {
      this.createEditor(editors[key]);
    }
  }

  /**
   * Create a new editor
   *
   * @param editor - the editor to create
   */
  createEditor(editor: any): void {
    // Create the editor div
    var editorDiv =
      '<div id="aoc-editor-' +
      editor.id +
      '" class="aoc-editor ' +
      editor.cssClass +
      '"></div>';

    // Place the editor in the appropriate location
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

  /**
   * Move an editor to an action space
   *
   * @param editor - the editor to move
   * @param actionSpace - the action space to move the editor to
   */
  moveEditorToActionSpace(editor: any, actionSpace: any): void {
    // Get the editor div
    const editorDiv = dojo.byId("aoc-editor-" + editor.id);
    // Get the action space div
    const actionSpaceDiv = dojo.query("[space$=" + actionSpace + "]")[0];

    // Create the animation to move the editor to the action space
    var animation = gameui.slideToObject(editorDiv, actionSpaceDiv);

    dojo.connect(animation, "onEnd", () => {
      // After animation, attach editor to new parent div
      gameui.attachToNewParent(editorDiv, actionSpaceDiv);
    });

    // Play the animation
    animation.play();
  }

  /**
   * Move an editor to the extra editor space
   */
  moveEditorToExtraEditorSpace(editor: any): void {
    // Get the editor div
    const editorDiv = dojo.byId("aoc-editor-" + editor.id);
    // Get the extra editor space div
    const extraEditorSpaceDiv = dojo.byId(
      "aoc-extra-editor-space-" + this.ui.getPlayerColorAsString(editor.color)
    );

    // Create the animation to move the editor to the extra editor space
    var animation = gameui.slideToObject(editorDiv, extraEditorSpaceDiv);

    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(editorDiv, "style");
      dojo.place(editorDiv, extraEditorSpaceDiv);
    });

    // Play the animation
    animation.play();
  }

  /**
   * Move an editor to a player area
   *
   * @param editor - the editor to move
   * @param playerId - the player to move the editor to
   */
  moveEditorToPlayerArea(editor: any, playerId: any): void {
    // Get the editor div
    const editorDiv = dojo.byId("aoc-editor-" + editor.id);
    // Get the player area div
    const playerAreaDiv = dojo.byId("aoc-editor-container-" + playerId);

    // Create the animation to move the editor to the player area
    var animation = gameui.slideToObject(editorDiv, playerAreaDiv);

    dojo.connect(animation, "onEnd", () => {
      dojo.removeAttr(editorDiv, "style");
      dojo.place(editorDiv, playerAreaDiv);
    });

    animation.play();
  }
}
