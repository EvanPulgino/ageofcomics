<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * AgeOfComics implementation : © Evan Pulgino <evan.pulgino@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * Backend functions used by the performPrintBonus State
 *
 * @EvanPulgino
 */

class AOCPerformPrintBonusState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    public function getArgs() {
        $printedComicId = $this->game->getGameStateValue(PRINTED_COMIC);
        $comic = $this->game->cardManager->getCard($printedComicId);

        return [
            "comicName" => $this->game->formatNotificationString(
                $comic->getName(),
                $comic->getGenreId()
            ),
            "comicBonus" => $comic->getBonus(),
        ];
    }

    public function stPerformPrintBonus() {
        $activePlayer = $this->game->playerManager->getActivePlayer();

        $printedComicId = $this->game->getGameStateValue(PRINTED_COMIC);
        $comic = $this->game->cardManager->getCard($printedComicId);
        $bonus = $comic->getBonus();

        switch ($bonus) {
            case PLUS_FOUR_MONEY:
                $this->plusFourMoney($activePlayer, $comic);
                break;
            case PLUS_ONE_FAN:
                $this->plusOneFan($activePlayer, $comic);
                break;
            case SUPER_TRANSPORT:
                $this->superTransport($activePlayer, $comic);
                break;
            case TWO_IDEAS:
                return;
        }

        $this->game->gamestate->nextState("nextPlayerTurn");
    }

    private function plusFourMoney($player, $comic) {
        $this->game->playerManager->adjustPlayerMoney($player, 4);
        $this->game->notifyAllPlayers(
            "adjustMoney",
            clienttranslate(
                '${player_name} earns 4 money from printing ${comicName}'
            ),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "amount" => 4,
                "comicName" => $this->game->formatNotificationString(
                    $comic->getName(),
                    $comic->getGenreId()
                ),
            ]
        );
    }

    private function plusOneFan($player, $comic) {
        $incomeChange = $this->game->miniComicManager->adjustMiniComicFans(
            $comic,
            1
        );
        $miniComic = $this->game->miniComicManager->getCorrespondingMiniComic(
            $comic
        );

        $this->game->playerManager->adjustPlayerIncome($player, $incomeChange);
        $this->game->notifyAllPlayers(
            "moveMiniComic",
            clienttranslate('${comicName} gains a bonus of 1 fan'),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "comicName" => $this->game->formatNotificationString(
                    $comic->getName(),
                    $comic->getGenreId()
                ),
                "miniComic" => $miniComic->getUiData(),
                "incomeChange" => $incomeChange,
            ]
        );
    }

    private function superTransport($player, $comic) {
    }
}
