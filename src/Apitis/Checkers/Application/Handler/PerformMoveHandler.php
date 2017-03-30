<?php

namespace Apitis\Checkers\Application\Handler;


use Apitis\Checkers\Domain\Contexts\Game\Repository\Games;

class PerformMoveHandler
{

    /**
     * @var Games
     */
    private $games;

    /**
     * PerformMoveHandler constructor.
     * @param Games $games
     */
    public function __construct(Games $games)
    {
        $this->games = $games;
    }

    public function move()
    {
        
    }


}