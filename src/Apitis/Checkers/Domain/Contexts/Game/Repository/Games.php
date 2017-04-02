<?php

namespace Apitis\Checkers\Domain\Contexts\Game\Repository;


use Apitis\Checkers\Domain\Contexts\Game\Entity\Game;
use Apitis\Checkers\Domain\Contexts\Game\Repository\Exception\GameNotFoundException;
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