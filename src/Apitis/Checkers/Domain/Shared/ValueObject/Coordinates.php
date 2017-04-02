<?php

namespace Apitis\Checkers\Domain\Shared\ValueObject;


use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Exception\MisalignedCoordinatesException;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Exception\NoInBetweenCoordinatesException;

interface Coordinates
{


    /**
     * @return int
     */
    public function getX();
    /**
     * @return int
     */
    public function getY();

    /**
     * Calculate in-between two coordinates
     * @param Coordinates $other
     * @return Coordinates
     * @throws NoInBetweenCoordinatesException
     */
    public function between(Coordinates $other);

    /**
     * Calculate coordinate just after target coordinate assuming moving in straight line from this coordinate
     * @param Coordinates $coordinates
     * @return Coordinates
     * @throws MisalignedCoordinatesException
     */
    public function after(Coordinates $coordinates);

    public function next(Direction $direction);

    public function direction(Coordinates $coordinates);

    /**
     * We define an axis as a straight line upon which pieces can move
     */

    public function getUpwardsAxis();

    public function getDownwardsAxis();
    
}