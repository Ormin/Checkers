<?php
/**
 * Created by PhpStorm.
 * Date: 4/2/17
 * Time: 11:20 PM
 */

namespace Apitis\Checkers\Application\Command;


use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;

class PerformMove
{
    
    private $gameId;

    /**
     * @var Coordinates
     */
    private $from;

    /**
     * @var Coordinates
     */
    private $to;

    /**
     * PerformMove constructor.
     * @param Coordinates $to
     * @param Coordinates $from
     */
    public function __construct(GameId $gameId, Coordinates $to, Coordinates $from)
    {
        $this->gameId = $gameId;
        $this->to = $to;
        $this->from = $from;
    }

    /**
     * @return GameId
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @return Coordinates
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return Coordinates
     */
    public function getTo()
    {
        return $this->to;
    }


}