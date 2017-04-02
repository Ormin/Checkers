<?php

namespace Apitis\Checkers\Domain\Contexts\Game\Event;


use Apitis\Checkers\Domain\Contexts\Game\Collection\CapturedPieces;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Move;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;

class MovePerformed
{

    /**
     * @var GameId
     */
    private $gameId;

    /**
     * @var Move
     */
    private $move;

    /**
     * @var CapturedPieces
     */
    private $capturedPieces;

    /**
     * MovePerformed constructor.
     * @param GameId $gameId
     * @param Move $move
     * @param CapturedPieces $capturedPieces
     */
    public function __construct(GameId $gameId, Move $move, CapturedPieces $capturedPieces)
    {
        $this->gameId = $gameId;
        $this->move = $move;
        $this->capturedPieces = $capturedPieces;
    }

    /**
     * @return GameId
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @return Move
     */
    public function getMove()
    {
        return $this->move;
    }

    /**
     * @return CapturedPieces
     */
    public function getCapturedPieces()
    {
        return $this->capturedPieces;
    }

}