<?php

namespace Apitis\Checkers\Application\Handler;


use Apitis\Checkers\Application\Command\PerformMove;
use Apitis\Checkers\Domain\Contexts\Game\Repository\Games;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Move;
use Apitis\Checkers\Domain\Shared\Identifiers\PlayerId;
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