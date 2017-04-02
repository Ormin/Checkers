<?php

namespace Apitis\Checkers\Domain\Contexts\Game\Entity;


use Apitis\Checkers\Domain\Contexts\Creation\Event\MovePerformed;
use Apitis\Checkers\Domain\Contexts\Game\Collection\FieldMatrix;
use Apitis\Checkers\Domain\Contexts\Game\Collection\Exception\NoPieceOnFieldException;
use Apitis\Checkers\Domain\Contexts\Game\Policy\Rules;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Field;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Move;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\MoveType;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Piece;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Exception\MisalignedCoordinatesException;
use Apitis\Checkers\Domain\Shared\Exception\FieldDoesNotExistException;
use Apitis\Checkers\Domain\Shared\Exception\IllegalMoveException;
use Apitis\Checkers\Domain\Shared\Exception\IllegalStateException;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\ValueObject\Color;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;
use Apitis\Checkers\Domain\Shared\ValueObject\Direction;
use Broadway\EventSourcing\SimpleEventSourcedEntity;

class Board extends SimpleEventSourcedEntity
{

    /**
     * @var Rules
     */
    private $rules;

    /**
     * @var integer
     */
    private $boardSize;

    /**
     * @var FieldMatrix
     */
    private $fields;


    /**
     * Board constructor.
     * @param FieldMatrix $fields
     */
    public function __construct($boardSize, FieldMatrix $fields, Rules $rules)
    {
        $this->boardSize = $boardSize;
        $this->fields = $fields;
        $this->rules = $rules;
    }

    /**
     * Try to move a piece on the board
     * @param GameId $gameId
     * @param Move $move
     * @throws IllegalMoveException The move is illegal in terms of this board rules
     * @throws NoPieceOnFieldException When trying to move a piece from a field that does not have a piece
     * @throws \Apitis\Checkers\Domain\Shared\Exception\CannotPickPieceException Piece cannot be picked
     * @throws FieldDoesNotExistException Either from/to fields do not exist
     * @return bool Whether further moves are allowed on the board
     */
    public function tryToMove(GameId $gameId, Move $move)
    {
        $color = $this->fields->getField($move->getFrom())->getPiecesColor();

        $moveType = $this->determineMoveType($move, $color);

        if($this->rules->isCapturingMandatory() &&
           $moveType == MoveType::NORMAL() &&
           $this->hasCaptureMove($move->getFrom()))
        {
            throw new IllegalMoveException("Piece making a normal move while a capture one is available");
        }

        $this->apply(new MovePerformed($gameId, $move));

        return ($moveType == MoveType::CAPTURE());
    }

    private function hasCaptureMove(Coordinates $coordinates)
    {
        $field = $this->fields->getField($coordinates);
        $color = $field->getPiecesColor();
        $jumpLength = ($field->isPieceAKing()) ? $this->rules->getBoardLength() : 1;
        foreach(Direction::members() as $direction) {
            $enemyPieceCoordinates = $this->findFirstEnemyPiece(
                $coordinates,
                $direction,
                $color,
                $jumpLength
            );

            if($enemyPieceCoordinates)
            {
                $landingFieldCoordinates = $coordinates->after($enemyPieceCoordinates);

                /**
                 * If field directly after the enemy piece is free, we can land here and capture move is possible
                 * If field is not available though, it means that we've got at least two pieces lined up and jump is
                 * not possible
                 */
                if(!$this->fields->hasPiece($landingFieldCoordinates))
                {
                    return true;
                }

            }

        }

        return false;
    }

    /**
     * Determine, if it's a legal normal move or a capture move.
     * @param Move $move
     * @param Color $color
     * @return MoveType
     * @throws IllegalMoveException Move is neither a legal normal move or a capture move
     * @throws NoPieceOnFieldException
     * @throws FieldDoesNotExistException
     */
    private function determineMoveType(Move $move, Color $color)
    {
        if($this->fields->getField($move->getTo())->hasPiece())
        {
            throw new IllegalMoveException("Occupied field - cannot move here.");
        }

        if($this->fields->getField($move->getFrom())->isPieceAKing()) {

            $enemyPieceCoordinate = $this->findFirstEnemyPieceOnWayOf($move, $color, $this->rules->getBoardLength());

            if($this->rules->doKingsStopOnFieldAfterCapture()) {

                if($enemyPieceCoordinate) {

                    if ($move->getFrom()->after($enemyPieceCoordinate) != $move->getTo()) {
                        throw new IllegalMoveException("King has stop on field directly after captured piece.");
                    }
                }

            }

            if($enemyPieceCoordinate) {
                return MoveType::CAPTURE();
            }

            return MoveType::NORMAL();
        } else {

            $enemyPieceCoordinate = $this->findFirstEnemyPieceOnWayOf($move, $color, 1);

            if($move->getDistance() == 1 && !$enemyPieceCoordinate)
            {
                if(!$this->isMoveForward($move, $color))
                {
                    throw new IllegalMoveException("Move cannot be backwards when you're not a king and not capturing");
                }

                return MoveType::NORMAL();
            } elseif($move->getDistance() == 2 && $enemyPieceCoordinate)
            {
                return MoveType::CAPTURE();
            } else {
                throw new IllegalMoveException("Cannot move more than one field");
            }

        }
    }

    private function findFirstEnemyPieceOnWayOf(Move $move, Color $color, $length)
    {
        $direction = $move->getFrom()->direction($move->getTo());
        return $this->findFirstEnemyPiece($move->getFrom(), $direction, $color, $length);
    }

    /**
     * Find the first enemy piece from given coordinates in target direction
     * @param Coordinates $coordinates Coordinates we check from
     * @param Direction $direction Target direction
     * @param Color $color
     * @param $length
     * @return Coordinates|null
     * @throws FieldDoesNotExistException
     * @throws NoPieceOnFieldException
     * @throws MisalignedCoordinatesException
     */
    private function findFirstEnemyPiece(Coordinates $coordinates, Direction $direction, Color $color, $length)
    {
        $currentCoordinates = $coordinates->next($direction);

        $k = 0;

        while($this->coordinatesOnBoard($currentCoordinates) && $k < $length)
        {
            if($this->fields->hasPiece($currentCoordinates))
            {
                if($this->fields->getField($currentCoordinates)->getPiecesColor() == $color->getOpposedColor())
                {
                    return $currentCoordinates;
                } else {
                    return null; //It's not an opposing piece
                }
            }

            $currentCoordinates = $currentCoordinates->next($direction);
            ++$k;
        }

        return null;

    }

    private function coordinatesOnBoard(Coordinates $coordinates)
    {
        return ($coordinates->getX() > 0 && $coordinates->getX() <= $this->rules->getBoardLength() &&
            $coordinates->getY() > 0 && $coordinates->getY() <= $this->rules->getBoardLength());
    }

    private function isMoveForward(Move $move, Color $color)
    {
        switch($color)
        {
            case Color::WHITE(): {
                return ($move->getTo()->getY() > $move->getFrom()->getY());
            }

            case Color::BLACK(): {
                return ($move->getTo()->getY() < $move->getFrom()->getY());
            }

        }


        return false; //Should never be reached
    }

    protected function applyMovePerformedEvent(MovePerformed $event)
    {
        $move = $event->getMove();
        $piece = $this->fields->getField($move->getFrom())->pickUp();
        $moveToField = $this->fields->getField($move->getTo());
        $moveToField->putDown($piece);
    }


    /**
     * Get the color of the piece on given coordinate
     * @param Coordinates $on
     * @return Color|null
     */
    public function getPiecesColor(Coordinates $on)
    {
        $field = $this->fields->getField($on);
        if(!$field) {
            return null;
        }

        return $field->getPiecesColor();
    }

    /**
     * Create a fresh board
     * @param Rules $rules
     * @return Board
     * @throws IllegalStateException
     */
    public static function create(Rules $rules)
    {

        /**
         * Number of rows filled with pieces equals number of pieces / (length of board / 2)
         */
        $numberOfRows = $rules->howManyPiecesPerSide() / ($rules->getBoardLength() / 2);

        if($numberOfRows >= ($rules->getBoardLength() / 2)) {
            throw new IllegalStateException("Invalid rules provided - too many pieces rows setup.");
        }

        /**
         * @todo - Make immutable in terms of fields ( remove addField and constructor injection )
         */
        $fields = new FieldMatrix();

        for($i = 1; $i <= $numberOfRows; ++$i)
        {
            self::addRowOfPieces($rules, $fields, $i, Color::WHITE());
        }

        for($i = $rules->getBoardLength(); $i > ($rules->getBoardLength() - $numberOfRows); --$i) {
            self::addRowOfPieces($rules, $fields, $i, Color::BLACK());
        }

        return new Board($rules->getBoardLength(), $fields, $rules);
    }

    private static function addRowOfPieces(Rules $rules, FieldMatrix $fields, $rowNumber, Color $color)
    {
        //Check the oddity of the row and at which X we should start counting
        if($rowNumber % 2 == 0) {
            $startingX = 2;
        } else {
            $startingX = 1;
        }

        for(; $startingX <= $rules->getBoardLength(); $startingX = $startingX + 2) {
            $field = new Field();
            $fields->addField(new Coordinates($startingX, $rowNumber), $field);
            $piece = new Piece($color);
            $field->putDown($piece);
        }
    }
}