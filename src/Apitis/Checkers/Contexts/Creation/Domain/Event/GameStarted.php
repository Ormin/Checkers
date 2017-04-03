<?php
/**
 * Created by PhpStorm.
 * Date: 3/30/17
 * Time: 3:59 PM
 */

namespace Apitis\Checkers\Contexts\Creation\Domain\Event;


use Apitis\Checkers\Contexts\Creation\Domain\Entity\Player;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;

class GameStarted
{

    /**
     * @var GameId
     */
    private $gameId;

    /**
     * @var Player
     */
    private $whitesPlayer;

    /**
     * @var Player
     */
    private $blacksPlayer;

    /**
     * GameStarted constructor.
     * @param GameId $gameId
     * @param Player $whitesPlayer
     * @param Player $blacksPlayer
     */
    public function __construct(GameId $gameId, Player $whitesPlayer, Player $blacksPlayer)
    {
        $this->gameId = $gameId;
        $this->whitesPlayer = $whitesPlayer;
        $this->blacksPlayer = $blacksPlayer;
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
    public function getWhitesPlayer()
    {
        return $this->whitesPlayer;
    }

    /**
     * @return Player
     */
    public function getBlacksPlayer()
    {
        return $this->blacksPlayer;
    }

    


}