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
 * In this state, players can hire creatives from the market. Players mus hire one artist and one writer.
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

    function hireCreative($cardId, $creativeType) {
        $activePlayerId = $this->game->getActivePlayerId();
        $activePlayer = $this->game->playerManager->getPlayer($activePlayerId);

        $cardTypeId =
            $creativeType == "artist" ? CARD_TYPE_ARTIST : CARD_TYPE_WRITER;

        // Draw the chosen card into the player's hand
        $card = $this->game->cardManager->drawCard(
            $activePlayerId,
            $cardId,
            $cardTypeId
        );

        // If the card is value 1, gain a matching idea
        if ($card->getIdeas() == 1) {
            $this->gainIdeaFromHiringCreative(
                $activePlayer,
                $card
            );
        }

        // Notify all players of the hire (with facedown card), and notify the active player privately (so they can see the card they hired)
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

        // If the player hired an artist, set the CAN_HIRE_ARTIST game state value to 0
        if ($creativeType == "artist") {
            $this->game->setGameStateValue(CAN_HIRE_ARTIST, 0);
        }

        // If the player hired a writer, set the CAN_HIRE_WRITER game state value to 0
        if ($creativeType == "writer") {
            $this->game->setGameStateValue(CAN_HIRE_WRITER, 0);
        }

        // If the player has hired both an artist and a writer, move to the next state
        if (
            $this->game->getGameStateValue(CAN_HIRE_ARTIST) == 0 &&
            $this->game->getGameStateValue(CAN_HIRE_WRITER) == 0
        ) {
            // If the player has more than 6 cards in their hand, move to the discardCards state
            // Otherwise, move to the nextPlayerTurn state
            if (
                $this->game->cardManager->getCountForHandSizeCheck(
                    $activePlayerId
                ) > 6
            ) {
                $this->game->gamestate->nextState("discardCards");
            } else {
                $this->game->gamestate->nextState("nextPlayerTurn");
            }
        } else {
            // If the player has not hired both an artist and a writer, move to the performNextHire state
            $this->game->gamestate->nextState("performNextHire");
        }
    }

    /**
     * A player gains an idea from hiring a value 1 creative
     *
     * @param AOCPlayer $player The player gaining the idea
     * @param AOCCard $card The creative card that was hired
     * @return void
     */
    private function gainIdeaFromHiringCreative($player, $card) {
        $this->game->playerManage->adjustPlayerIdeas(
            $player->getId(),
            1,
            $card->getGenre()
        );

        $this->game->notifyAllPlayers(
            "gainIdeaFromHiringCreative",
            clienttranslate(
                '${player_name} gains a ${genre} idea from hiring ${card_type_singular}'
            ),
            [
                "player" => $player->getUiData(),
                "player_name" => $player->getName(),
                "card" => $card->getUiData($player->getId()),
                "genre" => $card->getGenre(),
                "card_type_singular" =>
                    $card->getTypeId() == CARD_TYPE_ARTIST
                        ? "an artist"
                        : "a writer",
            ]
        );
    }
}
