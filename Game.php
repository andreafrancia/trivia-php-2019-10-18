<?php
function echoln($string) {
  echo $string."\n";
}

class Game {
    private $players;
    private $places;
    private $purses ;
    private $inPenaltyBox ;

    private $popQuestions;
    private $scienceQuestions;
    private $sportsQuestions;
    private $rockQuestions;

    private $currentPlayer = 0;
    private $isGettingOutOfPenaltyBox;

    function  __construct(){

   	$this->players = array();
        $this->places = array(0);
        $this->purses  = array(0);
        $this->inPenaltyBox  = array(0);

        $this->popQuestions = array();
        $this->scienceQuestions = array();
        $this->sportsQuestions = array();
        $this->rockQuestions = array();

        for ($i = 0; $i < 50; $i++) {
			array_push($this->popQuestions, "Pop Question " . $i);
			array_push($this->scienceQuestions, ("Science Question " . $i));
			array_push($this->sportsQuestions, ("Sports Question " . $i));
			array_push($this->rockQuestions, $this->createRockQuestion($i));
    	}
    }

	function createRockQuestion($index){
		return "Rock Question " . $index;
	}

	function isPlayable() {
		return ($this->howManyPlayers() >= 2);
	}

	public function add($playerName): Game
    {
	   $this->players[] = $playerName;
	   $this->places[$this->howManyPlayers()] = 0;
	   $this->purses[$this->howManyPlayers()] = 0;
	   $this->inPenaltyBox[$this->howManyPlayers()] = false;

	    echoln($playerName . ' was added');
	    echoln('They are player number ' . count($this->players));
	    
		return $this;
	}

	public function howManyPlayers(): int
    {
		return count($this->players);
	}

	function  roll($roll) {
		echoln($this->players[$this->currentPlayer] . " is the current player");
		echoln("They have rolled a " . $roll);

		if ($this->inPenaltyBox[$this->currentPlayer]) {
			if ($roll % 2 != 0) {
				$this->isGettingOutOfPenaltyBox = true;

				echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
			$this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
				if ($this->places[$this->currentPlayer] > 11) $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;

				echoln($this->players[$this->currentPlayer]
						. "'s new location is "
						.$this->places[$this->currentPlayer]);
				echoln("The category is " . $this->currentCategory());
				$this->askQuestion();
			} else {
				echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
				$this->isGettingOutOfPenaltyBox = false;
				}

		} else {

		$this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
			if ($this->places[$this->currentPlayer] > 11) $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;

			echoln($this->players[$this->currentPlayer]
					. "'s new location is "
					.$this->places[$this->currentPlayer]);
			echoln("The category is " . $this->currentCategory());
			$this->askQuestion();
		}

	}

	function  askQuestion() {
		if ($this->currentCategory() == "Pop")
			echoln(array_shift($this->popQuestions));
		if ($this->currentCategory() == "Science")
			echoln(array_shift($this->scienceQuestions));
		if ($this->currentCategory() == "Sports")
			echoln(array_shift($this->sportsQuestions));
		if ($this->currentCategory() == "Rock")
			echoln(array_shift($this->rockQuestions));
	}


    /**
     * Returns the current category
     * @return string
     */
    public function currentCategory(): string
    {
        switch ($this->places[$this->currentPlayer]) {
            case 0:
            case 4:
            case 8:
                $currentCategory = 'Pop';
                break;
            case 1:
            case 5:
            case 9:
                $currentCategory = 'Science';
                break;
            case 2:
            case 6:
            case 10:
                $currentCategory = 'Sports';
                break;
            default:
                $currentCategory = 'Rock';
                break;
        }

        return $currentCategory;
	}

    public function wasCorrectlyAnswered(): ?bool
    {
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->isGettingOutOfPenaltyBox) {
                echoln('Answer was correct!!!!');
                return $this->printCurrentStatus();
            }

            $this->currentPlayer++;
            if ($this->currentPlayer === count($this->players)) {
                $this->currentPlayer = 0;
            }
            return true;


        }

        echoln('Answer was corrent!!!!');
        return $this->printCurrentStatus();
    }

    /**
     * Print the wrong answer
     * @return bool
     */
    public function wrongAnswer(): bool
    {
        echoln('Question was incorrectly answered');
        echoln($this->players[$this->currentPlayer] . ' was sent to the penalty box');
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->currentPlayer++;
        if ($this->currentPlayer === count($this->players)) {
            $this->currentPlayer = 0;
        }

        return true;
    }


    public function didPlayerWin(): bool
    {
        return !($this->purses[$this->currentPlayer] === 6);
    }

    /**
     * @return bool
     */
    private function printCurrentStatus(): bool
    {
        $this->purses[$this->currentPlayer]++;
        echoln($this->players[$this->currentPlayer]
            . ' now has '
            . $this->purses[$this->currentPlayer]
            . ' Gold Coins.');

        $winner = $this->didPlayerWin();
        $this->currentPlayer++;
        if ($this->currentPlayer === count($this->players)) {
            $this->currentPlayer = 0;
        }

        return $winner;
    }
}
