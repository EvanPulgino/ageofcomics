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
    private int $id;
    private int $naturalOrder;
    private int $turnOrder;
    private string $name;
    private string $color;
    private string $colorAsText;
    private int $score;
    private int $scoreAux;
    private int $money;
    private int $crimeIdeas;
    private int $horrorIdeas;
    private int $romanceIdeas;
    private int $scifiIdeas;
    private int $superheroIdeas;
    private int $westernIdeas;
    private int $agentLocation;
    private int $agentLocationArg;
    private bool $multiActive;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->naturalOrder = (int) $row["naturalOrder"];
        $this->turnOrder = (int) $row["turnOrder"];
        $this->name = $row["name"];
        $this->color = $row["color"];
        $this->colorAsText = $this->getColorString($row["color"]);
        $this->score = $row["score"];
        $this->scoreAux = $row["scoreAux"];
        $this->money = $row["money"];
        $this->crimeIdeas = $row["crimeIdeas"];
        $this->horrorIdeas = $row["horrorIdeas"];
        $this->romanceIdeas = $row["romanceIdeas"];
        $this->scifiIdeas = $row["scifiIdeas"];
        $this->superheroIdeas = $row["superheroIdeas"];
        $this->westernIdeas = $row["westernIdeas"];
        $this->agentLocation = $row["agentLocation"];
        $this->agentLocationArg = $row["agentLocationArg"];
        $this->multiActive = $row["multiActive"] == 1;
    }

    public function getId() {
        return $this->id;
    }
    public function getNaturalOrder() {
        return $this->naturalOrder;
    }
    public function getTurnOrder() {
        return $this->turnOrder;
    }
    public function getName() {
        return $this->name;
    }
    public function getColor() {
        return $this->color;
    }
    public function getColorAsText() {
        return $this->colorAsText;
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
    public function getAgentLocation() {
        return $this->agentLocation;
    }
    public function getAgentLocationArg() {
        return $this->agentLocationArg;
    }
    public function isMultiActive() {
        return $this->multiActive;
    }

    public function getUiData() {
        return [
            "id" => $this->id,
            "naturalOrder" => $this->naturalOrder,
            "turnOrder" => $this->turnOrder,
            "name" => $this->name,
            "color" => $this->color,
            "colorAsText" => $this->colorAsText,
            "score" => $this->score,
            "scoreAux" => $this->scoreAux,
            "money" => $this->money,
            "crimeIdeas" => $this->crimeIdeas,
            "horrorIdeas" => $this->horrorIdeas,
            "romanceIdeas" => $this->romanceIdeas,
            "scifiIdeas" => $this->scifiIdeas,
            "superheroIdeas" => $this->superheroIdeas,
            "westernIdeas" => $this->westernIdeas,
            "agentLocation" => $this->agentLocation,
            "agentLocationArg" => $this->agentLocationArg,
            "multiActive" => $this->multiActive,
        ];
    }

    private function getColorString($hexColor) {
        switch($hexColor) {
            case '8e514e':
                return 'brown';
            case 'e5977a':
                return 'salmon';
            case '5ba59f':
                return 'teal';
            case 'f5c86e':
                return 'yellow';
        }
        return '';
    }
}
