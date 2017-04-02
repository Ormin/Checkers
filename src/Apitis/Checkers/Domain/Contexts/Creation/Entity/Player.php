<?php

namespace Apitis\Checkers\Domain\Contexts\Creation\Entity;


use Apitis\Checkers\Domain\Shared\Identifiers\PlayerId;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

class Player extends EventSourcedAggregateRoot
{

    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * Player constructor.
     * @param PlayerId $playerId
     */
    public function __construct(PlayerId $playerId)
    {
        $this->playerId = $playerId;
    }

    public function getAggregateRootId()
    {
        return $this->playerId;
    }


}