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
 * AOCPlayer.class.php
 *
 * Player object
 *
 */

class AOCPlayer {
    private $game;
    private int $id;
    private int $naturalOrder;
    private string $name;
    private string $color;
    private int $score;
    private int $scoreAux;
    private int $money;
    private int $crimeIdeas;
    private int $horrorIdeas;
    private int $romanceIdeas;
    private int $scifiIdeas;
    private int $superheroIdeas;
    private int $westernIdeas;
    private bool $multiActive;

    public function __construct($game, $row) {
        $this->game = $game;
        $this->id = (int) $row["id"];
        $this->naturalOrder = (int) $row["naturalOrder"];
        $this->name = $row["name"];
        $this->color = $row["color"];
        $this->score = $row["score"];
        $this->scoreAux = $row["scoreAux"];
        $this->money = $row["money"];
        $this->crimeIdeas = $row["crimeIdeas"];
        $this->horrorIdeas = $row["horrorIdeas"];
        $this->romanceIdeas = $row["romanceIdeas"];
        $this->scifiIdeas = $row["scifiIdeas"];
        $this->superheroIdeas = $row["superheroIdeas"];
        $this->westernIdeas = $row["westernIdeas"];
        $this->multiActive = $row["multiActive"] == 1;
    }

    public function getId() {
        return $this->id;
    }
    public function getNaturalOrder() {
        return $this->naturalOrder;
    }
    public function getName() {
        return $this->name;
    }
    public function getColor() {
        return $this->color;
    }
    public function getScore() {
        return $this->score;
    }
    public function getScoreAux() {
        return $this->scoreAux;
    }
    public function getMoney() {
        return $this->money;
    }
    public function getCrimeIdeas() {
        return $this->crimeIdeas;
    }
    public function getHorrorIdeas() {
        return $this->horrorIdeas;
    }
    public function getRomanceIdeas() {
        return $this->romanceIdeas;
    }
    public function getScifiIdeas() {
        return $this->scifiIdeas;
    }
    public function getSuperheroIdeas() {
        return $this->superheroIdeas;
    }
    public function getWesternIdeas() {
        return $this->westernIdeas;
    }
    public function isMultiActive() {
        return $this->multiActive;
    }

    public function getUiData() {
        return [
            "id" => $this->id,
            "naturalOrder" => $this->naturalOrder,
            "name" => $this->name,
            "color" => $this->color,
            "score" => $this->score,
            "scoreAux" => $this->scoreAux,
            "money" => $this->money,
            "crimeIdeas" => $this->crimeIdeas,
            "horrorIdeas" => $this->horrorIdeas,
            "romanceIdeas" => $this->romanceIdeas,
            "scifiIdeas" => $this->scifiIdeas,
            "superheroIdeas" => $this->superheroIdeas,
            "westernIdeas" => $this->westernIdeas,
            "multiActive" => $this->multiActive,
        ];
    }
}
