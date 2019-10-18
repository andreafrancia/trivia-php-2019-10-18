<?php

use PHPUnit\Framework\TestCase;

define("ROOT", realpath(__DIR__ . "/.."));

class TriviaTest extends TestCase
{
    protected function setUp(): void
    {
        if(!file_exists(pathTo("reference.txt"))) {
            $this->runManyTimes(pathTo("reference.txt"));
            self::fail("Refence file created as `reference.txt' please re-run the tests");
        }
    }

    public function test(): void
    {
        $this->runManyTimes(pathTo("actual.txt"));

        $expected = file_get_contents(pathTo("reference.txt"));
        $actual = file_get_contents(pathTo("actual.txt"));
        self::assertEquals($expected, $actual);
    }

    function runManyTimes($path) {
        ob_start();
        for($i=0; $i < 50; $i++) {
            srand($i);
            include __DIR__ . "/../GameRunner.php";
        }
        $output = ob_get_contents();
        ob_end_clean();
        file_put_contents($path, $output);
    }
}

function pathTo($basename)
{
    return realpath(__DIR__ . "/..") . "/$basename";
}
