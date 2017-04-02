<?php

namespace Apitis\Checkers\Domain\Contexts\Game\Event;

use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Contexts\Game\Entity\Player;

class GameFinished
{

    /**
     * @var GameId
     */
    private $gameId;

    /**
     * @var Player
     */
    private $winningPlayer;

    /**
     * @var Player
     */
    private $losingPlayer;

    /**
     * MovePerformed constructor.
     * @param GameId $gameId
     * @param Player $winningPlayer
     * @param Player $losingPlayer
     */
    public function __construct(GameId $gameId, Player $winningPlayer, Player $losingPlayer)
    {
        $this->gameId = $gameId;
        $this->winningPlayer = $winningPlayer;
        $this->losingPlayer = $losingPlayer;
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
    public function getWinningPlayer()
    {
        return $this->winningPlayer;
    }

    /**
     * @return Player
     */
    public function getLosingPlayer()
    {
        return $this->losingPlayer;
    }


}