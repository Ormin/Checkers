<?php

namespace Apitis\Checkers\Domain\Contexts\Game\ValueObject;

use Apitis\Checkers\Domain\Contexts\Game\Collection\Exception\NoPieceOnFieldException;
use Apitis\Checkers\Domain\Shared\Exception\CannotPickPieceException;
use Apitis\Checkers\Domain\Shared\Exception\CannotPutPieceException;
use Apitis\Checkers\Domain\Shared\ValueObject\Color;

class Field
{

    /**
     * @var Piece
     */
    private $piece;

    /**
     * Field constructor.
     * @param Piece $piece
     * @param Color $promotes The color for which pieces will get promoted to kings
     */
    public function __construct(Piece $piece = null, Color $promotes = null)
    {
        $this->piece = $piece;
    }

    /**
     * @return Piece
     * @throws CannotPickPieceException
     */
    public function pickUp()
    {
        if(!$this->hasPiece()) {
            throw new CannotPickPieceException();
        }

        $piece = $this->piece->pickUp();
        $this->piece = null;
        return $piece;
    }

    public function putDown(Piece $piece)
    {
        if($this->hasPiece()) {
            throw new CannotPutPieceException();
        }

        $this->piece = $piece->putDown();
    }

    public function getPiecesColor()
    {
        if(!$this->hasPiece()) {
            throw new NoPieceOnFieldException();
        }

        return $this->piece->getColor();
    }

    public function getPiecesJumpRange()
    {
        if(!$this->hasPiece()) {
            throw new NoPieceOnFieldException();
        }

        return $this->piece->getPiecesJumpRange();
    }

    public function isPieceAKing()
    {
        if(!$this->hasPiece()) {
            throw new NoPieceOnFieldException();
        }

        return $this->piece->isKing();
    }

    public function hasPiece()
    {
        return $this->piece !== null;
    }

}