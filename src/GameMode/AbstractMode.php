<?php

namespace ChessServer\GameMode;

use Chess\Game;
use ChessServer\Command\Ascii;
use ChessServer\Command\Castling;
use ChessServer\Command\Captures;
use ChessServer\Command\Fen;
use ChessServer\Command\HeuristicPicture;
use ChessServer\Command\History;
use ChessServer\Command\IsCheck;
use ChessServer\Command\IsMate;
use ChessServer\Command\Piece;
use ChessServer\Command\Pieces;
use ChessServer\Command\PlayFen;
use ChessServer\Command\Status;

abstract class AbstractMode
{
    protected $game;

    protected $resourceIds;

    protected $hash;

    public function __construct(Game $game, array $resourceIds)
    {
        $this->game = $game;
        $this->resourceIds = $resourceIds;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setGame(Game $game)
    {
        $this->game = $game;

        return $this;
    }

    public function getResourceIds(): array
    {
        return $this->resourceIds;
    }

    public function setResourceIds(array $resourceIds)
    {
        $this->resourceIds = $resourceIds;

        return $this;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function res($argv, $cmd)
    {
        try {
            switch (get_class($cmd)) {
                case Ascii::class:
                    return [
                        $cmd->name => $this->game->ascii(),
                    ];
                case Castling::class:
                    return [
                        $cmd->name => $this->game->castling(),
                    ];
                case Captures::class:
                    return [
                        $cmd->name => $this->game->captures(),
                    ];
                case Fen::class:
                    return [
                        $cmd->name => $this->game->fen(),
                    ];
                case HeuristicPicture::class:
                    return [
                        $cmd->name => [
                            'dimensions' => (new \Chess\HeuristicPicture(''))->getDimensions(),
                            'balance' => $this->game->heuristicPicture(true),
                        ],
                    ];
                case History::class:
                    return [
                        $cmd->name => $this->game->history(),
                    ];
                case IsCheck::class:
                    return [
                        $cmd->name => $this->game->isCheck(),
                    ];
                case IsMate::class:
                    return [
                        $cmd->name => $this->game->isCheck(),
                    ];
                case Piece::class:
                    return [
                        $cmd->name => $this->game->piece($argv[1]),
                    ];
                case Pieces::class:
                    return [
                        $cmd->name => $this->game->pieces($argv[1]),
                    ];
                case PlayFen::class:
                    return [
                        $cmd->name => [
                            'turn' => $this->game->status()->turn,
                            'legal' => $this->game->playFen($argv[1]),
                            'check' => $this->game->isCheck(),
                            'mate' => $this->game->isMate(),
                            'movetext' => $this->game->movetext(),
                            'fen' => $this->game->fen(),
                        ],
                    ];
                case Status::class:
                    return [
                        $cmd->name => $this->game->status(),
                    ];
                default:
                    return null;
            }
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}