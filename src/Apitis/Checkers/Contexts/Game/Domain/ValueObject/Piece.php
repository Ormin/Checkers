<?php

namespace Apitis\Checkers\Contexts\Game\Domain\ValueObject;

use Apitis\Checkers\Domain\Shared\Exception\CannotPickPieceException;
use Apitis\Checkers\Domain\Shared\ValueObject\Color;

class Piece
{

    /**
     * @var Color
     */
    private $color;

    /**
     * @var
     */
    private $isOnBoard = false;

    private $isKing = false;

    public function __construct(Color $color)
    {
        $this->color = $color;
    }

    /**
     * @return Piece
     * @throws CannotPickPieceException
     */
    public function pickUp()
    {
        if(!$this->isOnBoard)
        {
            throw new CannotPickPieceException("You can not pickup a piece not on board");
        }

        $this->isOnBoard = false;
        return $this;
    }

    public function putDown()
    {
        if(!$this->isOnBoard)
        {
            throw new CannotPickPieceException("You can not put down a piece already on board");
        }

        $this->isOnBoard = true;
        return $this;

    }

    /**
     * @return boolean
     */
    public function isKing()
    {
        return $this->isKing;
    }

    public function promote()
    {
        $this->isKing = true;
    }

    /**
     * @return Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Get pieces jump range.
     * @return int
     */
    public function getPiecesJumpRange()
    {
        return ($this->isKing) ? PHP_INT_MAX : 2;
    }

}