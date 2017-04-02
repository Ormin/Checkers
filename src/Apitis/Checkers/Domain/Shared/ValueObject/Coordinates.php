<?php

namespace Apitis\Checkers\Domain\Shared\ValueObject;


use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Exception\MisalignedCoordinatesException;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Exception\NoInBetweenCoordinatesException;

class Coordinates
{

    private $x;

    private $y;

    /**
     * Coordinates constructor.
     * @param integer $x
     * @param integer $y
     */
    public function __construct($x, $y)
    {

        /**
         * Checkers coordinates are valid when (x+y) % 2 = 0
         */

        if(($x + $y) / 2 != 0) {
            throw new \InvalidArgumentException("Invalid coordinates - x ".$x." y ".$y);
        }

        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Calculate in-between two coordinates
     * @param Coordinates $other
     * @return Coordinates
     * @throws NoInBetweenCoordinatesException
     */
    public function between(Coordinates $other)
    {

        if(($other->getY() + $this->getY()) % 2 != 0 ||
           ($other->getX() + $this->getX()) % 2 != 0
        ) {
            throw new NoInBetweenCoordinatesException();
        }

        return new Coordinates(
            ($other->getX() - $this->getX()) / 2,
            ($other->getY() - $this->getY()) / 2
        );
    }

    /**
     * Calculate coordinate just after target coordinate assuming moving in straight line from this coordinate
     * @param Coordinates $coordinates
     * @return Coordinates
     * @throws MisalignedCoordinatesException
     */
    public function after(Coordinates $coordinates)
    {
        $direction = $this->direction($coordinates);
        return $coordinates->next($direction);
    }

    public function next(Direction $direction) {
        switch($direction) {
            case Direction::NORTHEAST(): {
                return new Coordinates($this->getX() + 1, $this->getY() + 1);
            }
            case Direction::NORTHWEST(): {
                return new Coordinates($this->getX() - 1, $this->getY() + 1);

            }
            case Direction::SOUTHEAST(): {
                return new Coordinates($this->getX() + 1, $this->getY() - 1);
            }
            case Direction::SOUTHWEST(): {
                return new Coordinates($this->getX() - 1, $this->getY() - 1);
            }

            default: {
                throw new MisalignedCoordinatesException();
            }
        }
    }

    public function direction(Coordinates $coordinates)
    {
        if($this->getUpwardsAxis() == $coordinates->getUpwardsAxis()) {

            if($this->getX() < $coordinates->getX()) {
                return Direction::NORTHEAST();
            } else {
                return Direction::SOUTHWEST();
            }

        } elseif($this->getDownwardsAxis() == $coordinates->getDownwardsAxis()) {
            if($this->getX() < $coordinates->getX()) {
                return Direction::SOUTHEAST();
            } else {
                return Direction::NORTHWEST();
            }
        } else {
            throw new MisalignedCoordinatesException();
        }
    }

    /**
     * We define an axis as a straight line upon which pieces can move
     */

    public function getUpwardsAxis()
    {
        return $this->getY() - $this->getX();
    }

    public function getDownwardsAxis()
    {
        return $this->getY() + $this->getX();
    }


}