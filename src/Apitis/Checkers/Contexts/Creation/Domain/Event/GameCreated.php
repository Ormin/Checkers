<?php
/**
 * Created by PhpStorm.
 * Date: 3/30/17
 * Time: 3:59 PM
 */

namespace Apitis\Checkers\Contexts\Creation\Domain\Event;


use Apitis\Checkers\Domain\Shared\Identifiers\GameId;

class GameCreated
{

    /**
     * @var GameId
     */
    private $gameId;

    /**
     * PlayerJoinedAGame constructor.
     * @param GameId $gameId
     */
    public function __construct(GameId $gameId)
    {
        $this->gameId = $gameId;
    }

    /**
     * @return GameId
     */
    public function getGameId()
    {
        return $this->gameId;
    }

}