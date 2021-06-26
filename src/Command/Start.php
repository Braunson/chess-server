<?php

namespace ChessServer\Command;

use Chess\PGN\Symbol;
use ChessServer\Mode\Analysis;
use ChessServer\Mode\Friend;

class Start extends AbstractCommand
{
    public function __construct()
    {
        $this->name = '/start';
        $this->description = 'Starts a new game. The "color" parameter is not required in analysis mode.';
        $this->params = [
            'mode' => [
                Analysis::NAME,
                Friend::NAME,
            ],
            'color' => [
                Symbol::WHITE,
                Symbol::BLACK,
            ],
        ];
    }

    public function validate(array $argv)
    {
        if (in_array($argv[1], $this->params['mode'])) {
            switch ($argv[1]) {
                // the second parameter "color" is not required in analysis mode
                case Analysis::NAME:
                    return count($argv) - 1 === count($this->params) - 1;
                default:
                    return count($argv) - 1 === count($this->params) && in_array($argv[2], $this->params['color']);
            }
        }

        return false;
    }
}
