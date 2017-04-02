<?php
/**
 * Created by PhpStorm.
 * Date: 3/31/17
 * Time: 6:09 PM
 */

namespace Apitis\Checkers\Domain\Contexts\Game\ValueObject;

use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;
use Apitis\Checkers\Domain\Shared\ValueObject\Direction;

class MoveCoordinatesIterator implements \Iterator
{
    /**
     * @var Coordinates
     */
    private $current;

    /**
     * @var Coordinates
     */
    private $start;

    /**
     * @var Coordinates
     */
    private $end;

    /**
     * @var Direction
     */
    private $direction;

    private $k = 0;

    private $isFinished = false;

    public function __construct(Move $move)
    {
        $this->current = $this->start = $move->getFrom();
        $this->end = $move->getTo();
        $this->direction = $move->direction();
    }

    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        //Going out of loop
        if($this->current == $this->end)
        {
            $this->isFinished = true;
        } else {
            ++$this->k;
            $this->current->next($this->direction);
        }
    }

    public function key()
    {
        return $this->k;
    }

    public function valid()
    {
        return !($this->isFinished);
    }

    public function rewind()
    {
        $this->current = $this->start;
        $this->isFinished = false;
    }


}