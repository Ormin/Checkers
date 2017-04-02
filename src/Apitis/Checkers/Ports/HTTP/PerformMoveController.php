<?php
/**
 * Created by PhpStorm.
 * Date: 4/2/17
 * Time: 11:33 PM
 */

namespace Apitis\Checkers\Ports\HTTP;


use Apitis\Checkers\Application\Command\PerformMove;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\ValueObject\CartesianCoordinates;
use Broadway\CommandHandling\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class PerformMoveController
{
    private $commandBus;
    public function __construct(CommandBus $commandBus) {
        $this->commandBus = $commandBus;
    }

    public function handle(Request $request) {
        $this->commandBus->dispatch(
            new PerformMove(
                new GameId(Uuid::fromString($request->get('gameid'))),
                new CartesianCoordinates(
                    $request->get("fromx"),
                    $request->get("fromy")
                ),
                new CartesianCoordinates(
                    $request->get("fromx"),
                    $request->get("fromy")
                )
            )
        );

    }

}