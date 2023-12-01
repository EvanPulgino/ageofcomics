/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PlayerTurn.ts
 *
 * AgeOfComics player turn state
 *
 * State vars:
 *  game: game object reference
 *  connections: array of dojo connections
 *
 */

class PlayerTurn implements State {
  game: any;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = [];
  }

  onEnteringState(stateArgs: any): void {}

  /**
   * On leaving the player turn state, remove all the action buttons and click events
   */
  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");
    dojo.forEach(this.connections, dojo.disconnect);
  }

  /**
   * On update state, highlight the action spaces that are available to the player
   *
   * stateArgs:
   *  - isCurrentPlayerActive: true if the current player is the active player
   *
   * args:
   *  - hireActionSpace: the next available action space number for the hire action
   *  - developActionSpace: the next available action space number for the develop action
   *  - ideasActionSpace: the next available action space number for the ideas action
   *  - printActionSpace: the next available action space number for the print action
   *  - royaltiesActionSpace: the next available action space number for the royalties action
   *  - salesActionSpace: the next available action space number for the sales action
   *
   */
  onUpdateActionButtons(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      this.highlightInteractiveActionElements(
        "hire",
        "Hire",
        stateArgs.args.hireActionSpace
      );
      this.highlightInteractiveActionElements(
        "develop",
        "Develop",
        stateArgs.args.developActionSpace
      );
      this.highlightInteractiveActionElements(
        "ideas",
        "Ideas",
        stateArgs.args.ideasActionSpace
      );
      this.highlightInteractiveActionElements(
        "print",
        "Print",
        stateArgs.args.printActionSpace
      );
      this.highlightInteractiveActionElements(
        "royalties",
        "Royalties",
        stateArgs.args.royaltiesActionSpace
      );
      this.highlightInteractiveActionElements(
        "sales",
        "Sales",
        stateArgs.args.salesActionSpace
      );
    }
  }

  /**
   * Highlight the action board element and add the click event
   *
   * @param actionType the type of action
   * @param actionButtonText the text to display on the action button
   * @param actionSpace the action space number
   */
  highlightInteractiveActionElements(
    actionType: string,
    actionButtonText: string,
    actionSpace: number
  ): void {
    // Get the action board element and action button div ids
    const actionButtonDivId = "aoc-take-" + actionType + "-action";
    const actionBoardElementId = "aoc-action-" + actionType;

    // Create the action button
    gameui.addActionButton(actionButtonDivId, _(actionButtonText), () => {
      dojo.setAttr(actionButtonDivId, "data-action-space", actionSpace);
      this.selectAction(actionSpace);
    });

    dojo.addClass(actionButtonDivId, "aoc-button");

    // If the action space is 0 the player can't perform this action, disable the action button
    if (actionSpace == 0) {
      dojo.addClass(actionButtonDivId, "aoc-button-disabled");
    } else {
      // Highlight the action board element
      dojo.addClass(actionBoardElementId, "aoc-clickable");
      dojo.setAttr(actionBoardElementId, "data-action-space", actionSpace);
      // Add the click events
      this.connections.push(
        dojo.connect(
          dojo.byId(actionBoardElementId),
          "onclick",
          dojo.hitch(this, this.selectAction, actionSpace)
        )
      );
    }
  }

  /**
   * Select an action space, send the action to the server
   *
   * @param actionSpace the action space number
   */
  selectAction(actionSpace: number): void {
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_SELECT_ACTION_SPACE, {
      actionSpace: actionSpace,
    });
  }
}
