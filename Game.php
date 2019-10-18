<?php


class Game
{
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

    public function __construct()
    {

        $this->players = [];
        $this->places = [0];
        $this->purses = [0];
        $this->inPenaltyBox = [0];

        $this->popQuestions = [];
        $this->scienceQuestions = [];
        $this->sportsQuestions = [];
        $this->rockQuestions = [];

        for ($i = 0; $i < 50; $i++) {
            $this->popQuestions[] = $this->createQuestion('Pop', $i);
        }
        for ($i = 0; $i < 50; $i++) {
            $this->scienceQuestions[] = $this->createQuestion('Science', $i);
        }
        for ($i = 0; $i < 50; $i++) {
            $this->sportsQuestions[] = $this->createQuestion('Sports', $i);
        }
        for ($i = 0; $i < 50; $i++) {
            $this->rockQuestions[] = $this->createQuestion('Rock', $i);
        }
    }

	public function createQuestion($theme, $index): string
    {
		return "$theme Question $index";
	}

	public function add($playerName): Game
    {
	   $this->players[] = $playerName;
	   $this->places[$this->howManyPlayers()] = 0;
	   $this->purses[$this->howManyPlayers()] = 0;
	   $this->inPenaltyBox[$this->howManyPlayers()] = false;

	    $this->print($playerName . ' was added');
	    $this->print('They are player number ' . count($this->players));

		return $this;
	}

	public function howManyPlayers(): int
    {
		return count($this->players);
	}

    public function roll($roll): void
    {
        $this->print($this->players[$this->currentPlayer] . ' is the current player');
        $this->print('They have rolled a ' . $roll);

        if ($this->isInPenalty($this->currentPlayer)) {
            if ($roll % 2 !== 0) {
                $this->isGettingOutOfPenaltyBox = true;

                $this->print($this->players[$this->currentPlayer] . ' is getting out of the penalty box');
                $this->applyRoll($roll);
            } else {
                $this->print($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
                $this->isGettingOutOfPenaltyBox = false;
            }

        } else {

            $this->applyRoll($roll);
        }

    }

	public function askQuestion(): void
    {
        switch ($this->currentCategory()) {
            case 'Pop':
                $this->print(array_shift($this->popQuestions));
                break;
            case 'Science':
                $this->print(array_shift($this->scienceQuestions));
            break;
            case'Sports':
                $this->print(array_shift($this->sportsQuestions));
            break;
            case 'Rock':
                $this->print(array_shift($this->rockQuestions));
            break;
        }
	}

	private function print($string){
        echo $string."\n";
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
        if ($this->isInPenalty($this->currentPlayer)) {
            if ($this->isGettingOutOfPenaltyBox) {
                $this->print('Answer was correct!!!!');
                return $this->printCurrentStatus();
            }

            $this->currentPlayer++;
            if ($this->currentPlayer === count($this->players)) {
                $this->currentPlayer = 0;
            }
            return true;


        }

        $this->print('Answer was corrent!!!!');
        return $this->printCurrentStatus();
    }

    /**
     * Print the wrong answer
     * @return bool
     */
    public function wrongAnswer(): bool
    {
        $this->print('Question was incorrectly answered');
        $this->print($this->players[$this->currentPlayer] . ' was sent to the penalty box');
        $this->putInPenalty($this->currentPlayer);

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
        $this->print($this->players[$this->currentPlayer]
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

    /**
     * @param $roll
     */
    private function applyRoll($roll): void
    {
        $this->places[$this->currentPlayer] += $roll;
        if ($this->places[$this->currentPlayer] > 11) {
            $this->places[$this->currentPlayer] -= 12;
        }

        $this->print($this->players[$this->currentPlayer]
            . "'s new location is "
            . $this->places[$this->currentPlayer]);
        $this->print("The category is " . $this->currentCategory());
        $this->askQuestion();
    }

    /**
     * @return mixed
     */
    private function isInPenalty($player): bool
    {
        return $this->inPenaltyBox[$player];
    }

    private function putInPenalty($player): void
    {
        $this->inPenaltyBox[$player] = true;
    }
}
