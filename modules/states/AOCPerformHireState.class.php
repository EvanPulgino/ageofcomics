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
 * Backend functions used by the performHire State
 *
 * @EvanPulgino
 */

class AOCPerformHireState {
    private $game;

    public function __construct($game) {
        $this->game = $game;
    }

    /**
     * Gets the list of args used by the PerformHire state
     *
     * Args:
     * - canHireArtist => Whether the player has hired an artist on this turn or not
     * - canHireWriter => Whether the player has hired a writer on this turn or not
     * - hireText => The text to display in the game state panel based on the creatives a player has hired this turn
     *
     * @return array The list of args used by the PerformHire state
     */
    function getArgs() {
        $canHireArtist = $this->game->getGameStateValue(CAN_HIRE_ARTIST);
        $canHireWriter = $this->game->getGameStateValue(CAN_HIRE_WRITER);
        $hireText = "";

        if ($canHireArtist == 1 && $canHireWriter == 1) {
            $hireText = "one Artist and one Writer";
        } elseif ($canHireArtist == 1 && $canHireWriter == 0) {
            $hireText = "one Artist";
        } elseif ($canHireArtist == 0 && $canHireWriter == 1) {
            $hireText = "one Writer";
        }

        return [
            "canHireArtist" => $canHireArtist,
            "canHireWriter" => $canHireWriter,
            "hireText" => $hireText,
        ];
    }

    function hireCreative($args) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);
        $cardId = $args[0];
        $creativeType = $args[1];
        $cardTypeId =
            $creativeType == "artist" ? CARD_TYPE_ARTIST : CARD_TYPE_WRITER;

        $card = $this->game->cardManager->drawCard(
            $activePlayerId,
            $cardId,
            $cardTypeId
        );

        if ($card->getIdeas() == 1) {
            $this->game->playerManager->gainIdeaFromHiringCreative(
                $activePlayer,
                $card
            );
        }

        $this->game->notifyAllPlayers(
            "hireCreative",
            clienttranslate(
                '${player_name} hires a value ${cardValue} ${creative}'
            ),
            [
                "player" => $activePlayer->getUiData(),
                "player_id" => $activePlayerId,
                "player_name" => $activePlayer->getName(),
                "card" => $card->getUiData(0),
                "cardValue" => $card->getValue(),
                "creative" => ucfirst($creativeType),
            ]
        );
        $this->game->notifyPlayer(
            $activePlayerId,
            "hireCreativePrivate",
            clienttranslate('You hire a value ${cardValue} ${creative}'),
            [
                "player" => $activePlayer->getUiData(),
                "player_id" => $activePlayerId,
                "player_name" => $activePlayer->getName(),
                "card" => $card->getUiData($activePlayerId),
                "cardValue" => $card->getValue(),
                "creative" => ucfirst($creativeType),
            ]
        );

        if ($creativeType == "artist") {
            $this->game->setGameStateValue(CAN_HIRE_ARTIST, 0);
        }

        if ($creativeType == "writer") {
            $this->game->setGameStateValue(CAN_HIRE_WRITER, 0);
        }

        if (
            $this->game->getGameStateValue(CAN_HIRE_ARTIST) == 0 &&
            $this->game->getGameStateValue(CAN_HIRE_WRITER) == 0
        ) {
            if (
                count(
                    $this->game->cardManager->getPlayerHand($activePlayerId)
                ) > 6
            ) {
                $this->game->gamestate->nextState("discardCards");
            } else {
                $this->game->gamestate->nextState("nextPlayerTurn");
            }
        } else {
            $this->game->gamestate->nextState("performNextHire");
        }
    }
}
