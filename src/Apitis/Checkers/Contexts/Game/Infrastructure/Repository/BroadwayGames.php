<?php
/**
 * Created by PhpStorm.
 * Date: 4/3/17
 * Time: 12:12 AM
 */

namespace Apitis\Checkers\Contexts\Game\Infrastructure\Repository;


use Apitis\Checkers\Contexts\Game\Domain\Entity\Game;
use Apitis\Checkers\Contexts\Game\Domain\Repository\Games;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Broadway\EventStore\EventStore;

class BroadwayGames implements Games
{
    /**
     * @var EventStore
     */
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function save(Game $game)
    {
        $this->eventStore->append(
            $game->getAggregateRootId(),
            $game->getUncommittedEvents());
    }

    public function byId(GameId $gameId)
    {
        // TODO: Implement byId() method.
    }


}