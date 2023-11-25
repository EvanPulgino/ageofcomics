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
 * Object class for a Player that represents a player in the game.
 * Contains:
 * - The player's ID
 * - The player's natural order (this is set by the system and does not change)
 * - The player's turn order (the order players take turns during a round)
 * - The player's name
 * - The player's color
 * - The player's score
 * - The player's auxiliary (tiebreaker) score
 * - The player's Money
 * - The number of Crime Ideas the player has
 * - The number of Horror Ideas the player has
 * - The number of Romance Ideas the player has
 * - The number of Scifi Ideas the player has
 * - The number of Superhero Ideas the player has
 * - The number of Western Ideas the player has
 * - The location of the player's Sales Agent on the map
 * - The location of the player's first Special Action cube
 * - The location of the player's second Special Action cube
 * - The location of the player's third Special Action cube
 * - If the player is active in a multi-active state
 *
 * @EvanPulgino
 */

class AOCPlayer {
    /**
     * @var int $id The database ID of the player
     */
    private $id;

    /**
     * @var int $naturalOrder The natural order of the player, this is set by the system and does not change
     */
    private $naturalOrder;

    /**
     * @var int $turnOrder The turn order of the player, this the order players take turns during a round
     */
    private $turnOrder;

    /**
     * @var string $name The player's name
     */
    private $name;

    /**
     * @var string $color The player's color as a hex code
     */
    private $color;

    /**
     * @var string $colorAsText The player's color as a text string
     * - 8e514e = Brown
     * - e5977a = Salmon
     * - 5ba59f = Teal
     * - f5c86e = Yellow
     */
    private $colorAsText;

    /**
     * @var int $score The player's score
     */
    private $score;

    /**
     * @var int $scoreAux The player's auxiliary score, this is used to break ties at the end of the fame
     */
    private $scoreAux;

    /**
     * @var int $money The player's money
     */
    private $money;

    /**
     * @var int $crimeIdeas The number of crime ideas the player has
     */
    private $crimeIdeas;

    /**
     * @var int $horrorIdeas The number of horror ideas the player has
     */
    private $horrorIdeas;

    /**
     * @var int $romanceIdeas The number of romance ideas the player has
     */
    private $romanceIdeas;

    /**
     * @var int $scifiIdeas The number of scifi ideas the player has
     */
    private $scifiIdeas;

    /**
     * @var int $superheroIdeas The number of superhero ideas the player has
     */
    private $superheroIdeas;

    /**
     * @var int $westernIdeas The number of western ideas the player has
     */
    private $westernIdeas;

    /**
     * @var int $agentLocation The location of the player's sales agent on the map
     */
    private $agentLocation;

    /**
     * @var int $cubeOneLocation The location of the player's first special action cube
     * - 5 = On the player mat
     * - 10000 = On the Hire action
     * - 20000 = On the Develop action
     * - 30000 = On the Ideas action
     * - 40000 = On the Print action
     * - 50000 = On the Royalties action
     * - 60000 = On the Sales action
     */
    private $cubeOneLocation;

    /**
     * @var int $cubeTwoLocation The location of the player's second special action cube
     * - 5 = On the player mat
     * - 10000 = On the Hire action
     * - 20000 = On the Develop action
     * - 30000 = On the Ideas action
     * - 40000 = On the Print action
     * - 50000 = On the Royalties action
     * - 60000 = On the Sales action
     */
    private $cubeTwoLocation;

    /**
     * @var int $cubeThreeLocation The location of the player's third special action cube
     * - 5 = On the player mat
     * - 10000 = On the Hire action
     * - 20000 = On the Develop action
     * - 30000 = On the Ideas action
     * - 40000 = On the Print action
     * - 50000 = On the Royalties action
     * - 60000 = On the Sales action
     */
    private $cubeThreeLocation;

    /**
     * @var bool $multiActive Whether the player is active in a multi-active state
     */
    private bool $multiActive;

    public function __construct($row) {
        $this->id = (int) $row["id"];
        $this->naturalOrder = (int) $row["naturalOrder"];
        $this->turnOrder = (int) $row["turnOrder"];
        $this->name = $row["name"];
        $this->color = $row["color"];
        $this->colorAsText = PLAYER_COLORS[$row["color"]];
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
        $this->cubeOneLocation = $row["cubeOneLocation"];
        $this->cubeTwoLocation = $row["cubeTwoLocation"];
        $this->cubeThreeLocation = $row["cubeThreeLocation"];
        $this->multiActive = $row["multiActive"] == 1;
    }

    /**
     * Get the database ID of the player
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the natural order of the player, this is set by the system and does not change
     *
     * @return int
     */
    public function getNaturalOrder() {
        return $this->naturalOrder;
    }

    /**
     * Get the turn order of the player, this the order players take turns during a round
     *
     * @return int
     */
    public function getTurnOrder() {
        return $this->turnOrder;
    }

    /**
     * Get the player's name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get the player's color as a hex code
     *
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * Get the player's color as a text string
     * - 8e514e = Brown
     * - e5977a = Salmon
     * - 5ba59f = Teal
     * - f5c86e = Yellow
     *
     * @return string
     */
    public function getColorAsText() {
        return $this->colorAsText;
    }

    /**
     * Get the player's score
     *
     * @return int
     */
    public function getScore() {
        return $this->score;
    }

    /**
     * Set the player's score
     *
     * @param int $score The player's score
     * @return void
     */
    public function setScore($score) {
        $this->score = $score;
    }

    /**
     * Get the player's auxiliary score, this is used to break ties at the end of the fame
     *
     * @return int
     */
    public function getScoreAux() {
        return $this->scoreAux;
    }

    /**
     * Set the player's auxiliary score, this is used to break ties at the end of the fame
     *
     * @param int $scoreAux The player's auxiliary score
     * @return void
     */
    public function setScoreAux($scoreAux) {
        $this->scoreAux = $scoreAux;
    }

    /**
     * Get the player's money
     *
     * @return int
     */
    public function getMoney() {
        return $this->money;
    }

    /**
     * Set the player's money
     *
     * @param int $money The player's money
     * @return void
     */
    public function setMoney($money) {
        $this->money = $money;
    }

    /**
     * Get the number of crime ideas the player has
     *
     * @return int
     */
    public function getCrimeIdeas() {
        return $this->crimeIdeas;
    }

    /**
     * Set the number of crime ideas the player has
     *
     * @param int $crimeIdeas The number of crime ideas the player has
     * @return void
     */
    public function setCrimeIdeas($crimeIdeas) {
        $this->crimeIdeas = $crimeIdeas;
    }

    /**
     * Get the number of horror ideas the player has
     *
     * @return int
     */
    public function getHorrorIdeas() {
        return $this->horrorIdeas;
    }

    /**
     * Set the number of horror ideas the player has
     *
     * @param int $horrorIdeas The number of horror ideas the player has
     * @return void
     */
    public function setHorrorIdeas($horrorIdeas) {
        $this->horrorIdeas = $horrorIdeas;
    }

    /**
     * Get the number of romance ideas the player has
     *
     * @return int
     */
    public function getRomanceIdeas() {
        return $this->romanceIdeas;
    }

    /**
     * Set the number of romance ideas the player has
     *
     * @param int $romanceIdeas The number of romance ideas the player has
     * @return void
     */
    public function setRomanceIdeas($romanceIdeas) {
        $this->romanceIdeas = $romanceIdeas;
    }

    /**
     * Get the number of scifi ideas the player has
     *
     * @return int
     */
    public function getScifiIdeas() {
        return $this->scifiIdeas;
    }

    /**
     * Set the number of scifi ideas the player has
     *
     * @param int $scifiIdeas The number of scifi ideas the player has
     * @return void
     */
    public function setScifiIdeas($scifiIdeas) {
        $this->scifiIdeas = $scifiIdeas;
    }

    /**
     * Get the number of superhero ideas the player has
     *
     * @return int
     */
    public function getSuperheroIdeas() {
        return $this->superheroIdeas;
    }

    /**
     * Set the number of superhero ideas the player has
     *
     * @param int $superheroIdeas The number of superhero ideas the player has
     * @return void
     */
    public function setSuperheroIdeas($superheroIdeas) {
        $this->superheroIdeas = $superheroIdeas;
    }

    /**
     * Get the number of western ideas the player has
     *
     * @return int
     */
    public function getWesternIdeas() {
        return $this->westernIdeas;
    }

    /**
     * Set the number of western ideas the player has
     *
     * @param int $westernIdeas The number of western ideas the player has
     * @return void
     */
    public function setWesternIdeas($westernIdeas) {
        $this->westernIdeas = $westernIdeas;
    }

    /**
     * Get the number of ideas the player has of the given genre
     *
     * @param int $genreKey The genre key of the ideas to get
     * @return int
     */
    public function getIdeas($genreKey) {
        switch ($genreKey) {
            case GENRE_CRIME:
                return $this->crimeIdeas;
            case GENRE_HORROR:
                return $this->horrorIdeas;
            case GENRE_ROMANCE:
                return $this->romanceIdeas;
            case GENRE_SCIFI:
                return $this->scifiIdeas;
            case GENRE_SUPERHERO:
                return $this->superheroIdeas;
            case GENRE_WESTERN:
                return $this->westernIdeas;
            default:
                return 0;
        }
    }

    /**
     * Set the number of ideas the player has of the given genre
     *
     * @param int $genreKey The genre key of the ideas to set
     * @param int $ideas The number of ideas the player has of the given genre
     * @return void
     */
    public function setIdeas($genreKey, $ideas) {
        switch ($genreKey) {
            case GENRE_CRIME:
                $this->crimeIdeas = $ideas;
                break;
            case GENRE_HORROR:
                $this->horrorIdeas = $ideas;
                break;
            case GENRE_ROMANCE:
                $this->romanceIdeas = $ideas;
                break;
            case GENRE_SCIFI:
                $this->scifiIdeas = $ideas;
                break;
            case GENRE_SUPERHERO:
                $this->superheroIdeas = $ideas;
                break;
            case GENRE_WESTERN:
                $this->westernIdeas = $ideas;
                break;
        }
    }

    /**
     * Get the location of the player's sales agent on the map
     *
     * @return int
     */
    public function getAgentLocation() {
        return $this->agentLocation;
    }

    /**
     * Set the location of the player's sales agent on the map
     *
     * @param int $agentLocation The location of the player's sales agent on the map
     * @return void
     */
    public function setAgentLocation($agentLocation) {
        $this->agentLocation = $agentLocation;
    }

    /**
     * Get the location of the player's first special action cube
     * - 5 = On the player mat
     * - 10000 = On the Hire action
     * - 20000 = On the Develop action
     * - 30000 = On the Ideas action
     * - 40000 = On the Print action
     * - 50000 = On the Royalties action
     * - 60000 = On the Sales action
     *
     * @return int
     */
    public function getCubeOneLocation() {
        return $this->cubeOneLocation;
    }

    /**
     * Set the location of the player's first special action cube
     * - 5 = On the player mat
     * - 10000 = On the Hire action
     * - 20000 = On the Develop action
     * - 30000 = On the Ideas action
     * - 40000 = On the Print action
     * - 50000 = On the Royalties action
     * - 60000 = On the Sales action
     *
     * @param int $cubeOneLocation The location of the player's first special action cube
     * @return void
     */
    public function setCubeOneLocation($cubeOneLocation) {
        $this->cubeOneLocation = $cubeOneLocation;
    }

    /**
     * Get the location of the player's second special action cube
     * - 5 = On the player mat
     * - 10000 = On the Hire action
     * - 20000 = On the Develop action
     * - 30000 = On the Ideas action
     * - 40000 = On the Print action
     * - 50000 = On the Royalties action
     * - 60000 = On the Sales action
     *
     * @return int
     */
    public function getCubeTwoLocation() {
        return $this->cubeTwoLocation;
    }

    /**
     * Set the location of the player's second special action cube
     * - 5 = On the player mat
     * - 10000 = On the Hire action
     * - 20000 = On the Develop action
     * - 30000 = On the Ideas action
     * - 40000 = On the Print action
     * - 50000 = On the Royalties action
     * - 60000 = On the Sales action
     *
     * @param int $cubeTwoLocation The location of the player's second special action cube
     * @return void
     */
    public function setCubeTwoLocation($cubeTwoLocation) {
        $this->cubeTwoLocation = $cubeTwoLocation;
    }

    /**
     * Get the location of the player's third special action cube
     * - 5 = On the player mat
     * - 10000 = On the Hire action
     * - 20000 = On the Develop action
     * - 30000 = On the Ideas action
     * - 40000 = On the Print action
     * - 50000 = On the Royalties action
     * - 60000 = On the Sales action
     *
     * @return int
     */
    public function getCubeThreeLocation() {
        return $this->cubeThreeLocation;
    }

    /**
     * Set the location of the player's third special action cube
     * - 5 = On the player mat
     * - 10000 = On the Hire action
     * - 20000 = On the Develop action
     * - 30000 = On the Ideas action
     * - 40000 = On the Print action
     * - 50000 = On the Royalties action
     * - 60000 = On the Sales action
     *
     * @param int $cubeThreeLocation The location of the player's third special action cube
     * @return void
     */
    public function setCubeThreeLocation($cubeThreeLocation) {
        $this->cubeThreeLocation = $cubeThreeLocation;
    }

    /**
     * Get whether the player is active in a multi-active state
     *
     * @return bool
     */
    public function isMultiActive() {
        return $this->multiActive;
    }

    /**
     * Get the data formatted for the UI
     *
     * @return array
     */
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
            "cubeOneLocation" => $this->cubeOneLocation,
            "cubeTwoLocation" => $this->cubeTwoLocation,
            "cubeThreeLocation" => $this->cubeThreeLocation,
            "multiActive" => $this->multiActive,
        ];
    }
}
