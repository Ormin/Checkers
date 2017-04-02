<?php

namespace Apitis\Checkers\Domain\Shared\Identifiers;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PlayerId
{

    /**
     * @var integer
     */
    private $value;

    /**
     * PlayerId constructor.
     * @param UuidInterface $value
     */
    public function __construct(UuidInterface $value)
    {
        $this->value = $value;
    }

    public static function create()
    {
        return new PlayerId(Uuid::uuid4());
    }

    public function equals(PlayerId $gameId)
    {
        return $this->value->equals($gameId->value);
    }

    public function __toString()
    {
        return $this->value->toString();
    }


}