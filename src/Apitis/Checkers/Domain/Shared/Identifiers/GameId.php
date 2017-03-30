<?php

namespace Apitis\Checkers\Domain\Shared\Identifiers;

use Apitis\Checkers\Domain\Contexts\Game\Exception\InvalidIdentifierException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class GameId
{

    /**
     * @var integer
     */
    private $value;

    /**
     * GameId constructor.
     * @param UuidInterface $value
     * @throws InvalidIdentifierException
     */
    public function __construct(UuidInterface $value)
    {
        $this->value = $value;
    }

    public static function create()
    {
        return new GameId(Uuid::uuid4());
    }

    public function equals(GameId $gameId)
    {
        return $this->value->equals($gameId->value);
    }

    public function __toString()
    {
        return $this->value->toString();
    }


}