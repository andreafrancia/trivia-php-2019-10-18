<?php

require_once __DIR__ . '/Game.php';

$notAWinner = false;

$aGame = new Game();

$playersName = ['Chet', 'Pat', 'Sue'];
foreach ($playersName as $playerName) {
    $aGame->add($playerName);
}

do {
    $aGame->roll(rand(0, 5) + 1);
    $notAWinner = rand(0, 9) == 7
        ? $aGame->wrongAnswer()
        : $aGame->wasCorrectlyAnswered();
} while ($notAWinner);
