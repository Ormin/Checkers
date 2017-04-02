<?php
/**
 * Created by PhpStorm.
 * Date: 3/31/17
 * Time: 6:09 PM
 */

namespace Apitis\Checkers\Domain\Contexts\Game\ValueObject;


use Apitis\Checkers\Domain\Shared\Exception\IllegalMoveException;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;
use Apitis\Checkers\Domain\Shared\ValueObject\Iterator\CoordinatesIterator;

class Move implements \IteratorAggregate
{

    /**
     * @var Coordinates
     */
    private $from;

    /**
     * @var Coordinates
     */
    private $to;

    /**
     * Move constructor.
     * @param Coordinates $from
     * @param Coordinates $to
     * @throws IllegalMoveException
     */
    public function __construct(Coordinates $from, Coordinates $to)
    {
        /**
         * Valid move has to be on proper axis, as in - do not change direction
         */
        if($from->getDownwardsAxis() != $to->getDownwardsAxis() && $from->getUpwardsAxis() != $to->getUpwardsAxis())
        {
            throw new IllegalMoveException("A checkers move must be in line.");
        }

        $this->from = $from;
        $this->to = $to;


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

    public function direction()
    {
        return $this->from->direction($this->to);
    }

    public function getDistance()
    {
        $minX = min($this->from->getX(), $this->to->getX());
        $maxX = max($this->from->getX(), $this->to->getX());
        $minY = min($this->from->getY(), $this->to->getY());
        $maxY = max($this->from->getY(), $this->to->getY());

        $distX = $maxX - $minX;
        $distY = $maxY - $minY;

        return max($distX, $distY);
    }

    public function getIterator()
    {
        return new CoordinatesIterator($this->getFrom(), $this->getTo());
    }


}