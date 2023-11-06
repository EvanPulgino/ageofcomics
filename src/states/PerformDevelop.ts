/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * PerformDevelop.ts
 *
 */

class PerformDevelop implements State {
  game: any;
  connections: any;
  constructor(game: any) {
    this.game = game;
    this.connections = {};
  }

  onEnteringState(stateArgs: any): void {
    if (stateArgs.isCurrentPlayerActive) {
      this.createDevelopActions();
      if (stateArgs.args.canDevelopFromDeck) {
        this.createDevelopFromDeckActions(stateArgs.args.availableGenres);
      }
    }
  }

  onLeavingState(): void {
    dojo.query(".aoc-clickable").removeClass("aoc-clickable");

    for (var key in this.connections) {
      dojo.disconnect(this.connections[key]);
    }

    this.connections = {};

    var buttonRowDiv = dojo.byId("aoc-develop-from-deck-buttons");
    if (buttonRowDiv) {
      buttonRowDiv.remove();
    }
  }

  onUpdateActionButtons(stateArgs: any): void {}

  createDevelopActions(): void {
    var topCardOfDeck = dojo.byId("aoc-comic-deck").lastChild;
    topCardOfDeck.classList.add("aoc-clickable");
    var topCardOfDeckId = topCardOfDeck.id.split("-")[2];
    this.connections["comic" + topCardOfDeckId] = dojo.connect(
      dojo.byId(topCardOfDeck.id),
      "onclick",
      dojo.hitch(this, this.developComic, topCardOfDeckId)
    );

    var cardElements = dojo.byId("aoc-comics-available").children;

    for (var key in cardElements) {
      var card = cardElements[key];
      if (card.id) {
        card.classList.add("aoc-clickable");
        var cardId = card.id.split("-")[2];
        this.connections["comic" + cardId] = dojo.connect(
          dojo.byId(card.id),
          "onclick",
          dojo.hitch(this, this.developComic, cardId)
        );
      }
    }
  }

  createDevelopFromDeckActions(availableGenres: any): void {
    var buttonRowDiv =
      "<div id='aoc-develop-from-deck-buttons' class='aoc-action-panel-row'><div id='aoc-seach-icon' class='aoc-search-icon'></div></div>";
    this.game.createHtml(buttonRowDiv, "page-title");

    const genres = this.game.getGenres();
    for (var key in genres) {
      var genre = genres[key];
      var buttonDiv =
        "<div id='aoc-develop-from-deck-" +
        genre +
        "' class='aoc-mini-comic-card aoc-mini-comic-card-" +
        genre +
        "'></div>";
      this.game.createHtml(buttonDiv, "aoc-develop-from-deck-buttons");

      if (availableGenres[genre] > 0) {
        dojo.addClass("aoc-develop-from-deck-" + genre, "aoc-image-clickable");
        this.connections["developFromDeck" + genre] = dojo.connect(
          dojo.byId("aoc-develop-from-deck-" + genre),
          "onclick",
          dojo.hitch(this, this.developComicFromDeck, genre)
        );
      } else {
        dojo.addClass("aoc-develop-from-deck-" + genre, "aoc-image-disabled");
      }
    }
  }

  developComic(comicId: number): void {
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_DEVELOP_COMIC, {
      comicId: comicId,
    });
  }

  developComicFromDeck(genre: string): void {
    this.game.ajaxcallwrapper(globalThis.PLAYER_ACTION_DEVELOP_FROM_GENRE, {
      genre: genre,
    });
  }
}
