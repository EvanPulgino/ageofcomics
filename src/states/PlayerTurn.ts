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
      gameui.addActionButton("aoc-take-hire-action", _("Hire"), (event) => {
        console.log("hire");
      });
      gameui.addActionButton(
        "aoc-take-develop-action",
        _("Develop"),
        (event) => {
          console.log("develop");
        }
      );
      gameui.addActionButton("aoc-take-ideas-action", _("Ideas"), (event) => {
        console.log("ideas");
      });
      gameui.addActionButton("aoc-take-print-action", _("Print"), (event) => {
        console.log("print");
      });
      gameui.addActionButton(
        "aoc-take-royalties-action",
        _("Royalties"),
        (event) => {
          console.log("royalties");
        }
      );
      gameui.addActionButton("aoc-take-sales-action", _("Sales"), (event) => {
        console.log("sales");
      });
    }
  }
}
