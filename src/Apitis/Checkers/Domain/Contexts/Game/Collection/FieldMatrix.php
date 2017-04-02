<?php
/**
 * Created by PhpStorm.
 * Date: 3/31/17
 * Time: 1:35 PM
 */

namespace Apitis\Checkers\Domain\Contexts\Game\Collection;


use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Field;
use Apitis\Checkers\Domain\Shared\Exception\FieldDoesNotExistException;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;

class FieldMatrix
{

    /**
     * @var Field[][]
     */
    private $fields;


    public function addField(Coordinates $coordinates, Field $field)
    {
        if(!isset($this->fields[$coordinates->getX()])) {
            $this->fields[$coordinates->getX()] = [];
        }

        $this->fields[$coordinates->getX()][$coordinates->getY()] = $field;
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

}