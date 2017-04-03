<?php

namespace Apitis\Checkers\Contexts\Game\Domain\Repository;


use Apitis\Checkers\Contexts\Game\Domain\Entity\Game;
use Apitis\Checkers\Contexts\Game\Domain\Repository\Exception\GameNotFoundException;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;

interface Games
{
    /**
     * @param GameId $gameId
     * @throws GameNotFoundException
     * @return Game
     */
    public function byId(GameId $gameId);

    /**
     * @param Game $game
     * @return bool
     */
    public function save(Game $game);
}