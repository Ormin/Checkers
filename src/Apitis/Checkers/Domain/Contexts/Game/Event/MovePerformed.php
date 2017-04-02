<?php

namespace Apitis\Checkers\Domain\Contexts\Creation\Event;


use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Move;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;

class MovePerformed
{

    /**
     * @var GameId
     */
    private $gameId;

    private $move;

    /**
     * PlayerJoinedAGame constructor.
     * @param GameId $gameId
     */
    public function __construct(GameId $gameId, Move $move)
    {
        $this->gameId = $gameId;
        $this->move = $move;
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


    

}