<?php
/**
 * Created by PhpStorm.
 * Date: 3/30/17
 * Time: 3:59 PM
 */

namespace Apitis\Checkers\Contexts\Creation\Domain\Event;


use Apitis\Checkers\Contexts\Creation\Domain\Entity\Player;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\ValueObject\Color;

class PlayerJoinedAGame
{

    /**
     * @var GameId
     */
    private $gameId;

    /**
     * @var Player
     */
    private $player;

    /**
     * @var Color
     */
    private $color;

    /**
     * PlayerJoinedAGame constructor.
     * @param GameId $gameId
     * @param Player $player
     * @param Color $color
     */
    public function __construct(GameId $gameId, Player $player, Color $color)
    {
        $this->gameId = $gameId;
        $this->player = $player;
        $this->color = $color;
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

    /**
     * @return Color
     */
    public function getColor()
    {
        return $this->color;
    }

    

    

}