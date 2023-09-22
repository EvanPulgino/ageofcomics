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
 */

class PlayerTurn implements State {
  game: any;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = [];
  }

  onEnteringState(stateArgs: any): void {}
  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");
    dojo.forEach(this.connections, dojo.disconnect);
  }
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

  highlightInteractiveActionElements(
    actionType: string,
    actionButtonText: string,
    actionSpace: number
  ): void {
    const actionButtonDivId = "aoc-take-" + actionType + "-action";
    const actionBoardElementId = "aoc-action-" + actionType;

    // Create the action button
    gameui.addActionButton(actionButtonDivId, _(actionButtonText), (event) => {
      dojo.setAttr(actionButtonDivId, "data-action-space", actionSpace);
      this.selectAction(event);
    });

    dojo.addClass(actionButtonDivId, "aoc-button");
    if (actionSpace == 0) {
      dojo.addClass(actionButtonDivId, "aoc-button-disabled");
    } else {
      // Highlight the action board element
      dojo.addClass(actionBoardElementId, "aoc-clickable");
      dojo.setAttr(actionBoardElementId, "data-action-space", actionSpace);
      // Add the click events
      this.connections.push(dojo.connect(
        dojo.byId(actionBoardElementId),
        "onclick",
        dojo.hitch(this, "selectAction")
      ));
    }
  }

  selectAction(event): void {
    const actionSpace = parseInt(
      dojo.getAttr(event.target, "data-action-space")
    );

    switch (actionSpace) {
      case 50001:
        this.takeRoyalties(4, actionSpace);
        break;
      case 50002:
        this.takeRoyalties(3, actionSpace);
        break;
      case 50003:
        this.takeRoyalties(3, actionSpace);
        break;
      case 50004:
        this.takeRoyalties(2, actionSpace);
        break;
      case 50005:
        this.takeRoyalties(1, actionSpace);
        break;
    }
  }

  takeRoyalties(amount: number, space: number): void {
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_TAKE_ROYALTIES, {
      amount: amount,
      space: space,
    });
  }
}
