<?php
/**
 * Created by PhpStorm.
 * Date: 3/31/17
 * Time: 1:35 PM
 */

namespace Apitis\Checkers\Domain\Contexts\Game\Collection;


use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Field;
use Apitis\Checkers\Domain\Shared\Exception\FieldDoesNotExistException;
use Apitis\Checkers\Domain\Shared\ValueObject\Color;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;

class FieldMatrix
{

    /**
     * @var Field[][]
     */
    private $fields;

    private $piecesCounts = [];

    public function addField(Coordinates $coordinates, Field $field)
    {
        if(!isset($this->fields[$coordinates->getX()])) {
            $this->fields[$coordinates->getX()] = [];
        }

        if(!isset($this->piecesCounts[$field->getPiecesColor()->value()])) {
            $this->piecesCounts[$field->getPiecesColor()->value()] = 0;
        }

        $this->fields[$coordinates->getX()][$coordinates->getY()] = $field;
        $this->piecesCounts[$field->getPiecesColor()->value()]++;
    }

    /**
     * Gets the field basing on coordinates
     * @param Coordinates $coordinates
     * @return Field
     * @throws FieldDoesNotExistException
     */
    public function getField(Coordinates $coordinates)
    {
        if(!isset($this->fields[$coordinates->getX()])) {
            throw new FieldDoesNotExistException();
        }

        if(!isset($this->fields[$coordinates->getX()][$coordinates->getY()]))
        {
            throw new FieldDoesNotExistException();
        }

        return $this->fields[$coordinates->getX()][$coordinates->getY()];
    }

    public function hasPiece(Coordinates $coordinates)
    {
        if(!isset($this->fields[$coordinates->getX()])) {
            return false;
        }

        if(!isset($this->fields[$coordinates->getX()][$coordinates->getY()])) {
            return false;
        }

        return $this->fields[$coordinates->getX()][$coordinates->getY()]->hasPiece();
    }

    public function hasPiecesOfColorLeft(Color $color)
    {
        if(!isset($this->piecesCounts[$color->value()])) {
            return false;
        }

        return ($this->piecesCounts[$color->value()] > 0);
    }

}