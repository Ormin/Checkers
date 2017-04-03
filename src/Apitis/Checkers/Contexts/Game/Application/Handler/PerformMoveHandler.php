<?php

namespace Apitis\Checkers\Contexts\Game\Application\Handler;


use Apitis\Checkers\Contexts\Game\Application\Command\PerformMove;
use Apitis\Checkers\Contexts\Game\Domain\Repository\Games;
use Apitis\Checkers\Contexts\Game\Domain\ValueObject\Move;
use Broadway\CommandHandling\CommandHandler;

class PerformMoveHandler implements CommandHandler
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

    public function handle($command)
    {
        $this->performMove($command);
    }

    private function performMove(PerformMove $performMove)
    {
        $this->games->byId($performMove->getGameId())
            ->performMove(
            new Move(
                $performMove->getFrom(),
                $performMove->getTo()
            )
        );
    }

}