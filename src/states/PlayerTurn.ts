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
  constructor(game: any) {
    this.game = game;
  }

  onEnteringState(stateArgs: any): void {}
  onLeavingState(): void {}
  onUpdateActionButtons(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      if (stateArgs.args.hireActionSpace > 0) {
        this.highlightInteractiveActionElements("hire", "Hire");
      }
      if (stateArgs.args.developActionSpace > 0) {
        this.highlightInteractiveActionElements("develop", "Develop");
      }
      if (stateArgs.args.ideasActionSpace > 0) {
        this.highlightInteractiveActionElements("ideas", "Ideas");
      }
      if (stateArgs.args.printActionSpace > 0) {
        this.highlightInteractiveActionElements("print", "Print");
      }
      if (stateArgs.args.royaltiesActionSpace > 0) {
        this.highlightInteractiveActionElements("royalties", "Royalties");
      }
      if (stateArgs.args.salesActionSpace > 0) {
        this.highlightInteractiveActionElements("sales", "Sales");
      }
    }
  }

  highlightInteractiveActionElements(
    actionType: string,
    actionButtonText: string
  ): void {
    const actionButtonDivId = "aoc-take-" + actionType + "-action";
    const actionBoardElementId = "aoc-action-" + actionType;

    // Create the action button
    gameui.addActionButton(actionButtonDivId, _(actionButtonText), (event) => {
      console.log(actionType);
    });
    dojo.addClass(actionButtonDivId, "aoc-button");

    // Highlight the action board element
    dojo.addClass(actionBoardElementId, "aoc-clickable");
  }
}
