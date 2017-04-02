<?php

namespace Apitis\Checkers\Domain\Contexts\Game\Repository;


use Apitis\Checkers\Domain\Shared\Identifiers\GameId;

interface Games
{
    public function byId(GameId $gameId);
}