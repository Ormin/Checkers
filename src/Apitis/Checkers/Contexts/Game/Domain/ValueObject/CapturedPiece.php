<?php
/**
 * Created by PhpStorm.
 * Date: 3/31/17
 * Time: 6:09 PM
 */

namespace Apitis\Checkers\Contexts\Game\Domain\ValueObject;


use Apitis\Checkers\Domain\Shared\ValueObject\Color;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;
use Apitis\Checkers\Domain\Shared\ValueObject\Direction;

class CapturedPiece implements Coordinates
{

    /**
     * @var Coordinates
     */
    private $location;

    /**
     * @var Color
     */
    private $color;

    /**
     * CapturedPiece constructor.
     * @param Coordinates $location
     * @param Color $color
     */
    public function __construct(Coordinates $location, Color $color)
    {
        $this->location = $location;
        $this->color = $color;
    }

    /**
     * @return Piece
     */
    public function getCapturedPieceColor()
    {
        return $this->color;
    }

    public function getX()
    {
        return $this->location->getX();
    }

    public function getY()
    {
        return $this->location->getY();
    }

    public function between(Coordinates $other)
    {
        return $this->location->between($other);
    }

    public function after(Coordinates $coordinates)
    {
        return $this->location->after($coordinates);
    }

    public function next(Direction $direction)
    {
        return $this->location->next($direction);
    }

    public function direction(Coordinates $coordinates)
    {
        return $this->location->direction($coordinates);
    }

    public function getUpwardsAxis()
    {
        return $this->location->getUpwardsAxis();
    }

    public function getDownwardsAxis()
    {
        return $this->location->getDownwardsAxis();
    }


}