<?php

namespace Apitis\Checkers\Domain\Contexts\Creation\Event;


use Apitis\Checkers\Domain\Contexts\Game\Entity\Player;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Move;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;

class TurnEnded
{

    /**
     * @var GameId
     */
    private $gameId;

    private $player;

    public function __construct(GameId $gameId, Player $player)
    {
        $this->gameId = $gameId;
        $this->player = $player;
    }

    /**
     * @return GameId
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
    
}