<?php

namespace Apitis\Checkers\Domain\Contexts\Game\Entity;


use Apitis\Checkers\Domain\Contexts\Creation\Event\MovePerformed;
use Apitis\Checkers\Domain\Contexts\Game\Collection\CapturedPieces;
use Apitis\Checkers\Domain\Contexts\Game\Collection\FieldMatrix;
use Apitis\Checkers\Domain\Contexts\Game\Collection\Exception\NoPieceOnFieldException;
use Apitis\Checkers\Domain\Contexts\Game\Policy\Rules;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\CapturedPiece;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Field;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Move;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Piece;
use Apitis\Checkers\Domain\Shared\ValueObject\CartesianCoordinates;
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
        $capturedPieces = $this->checkMoveLegality($move);

        if($this->rules->isCapturingMandatory() &&
           !$capturedPieces->moreThanZero() &&
           $this->hasCaptureMove($move->getFrom()))
        {
            throw new IllegalMoveException("Piece making a normal move while a capture one is available");
        }

        $this->apply(new MovePerformed($gameId, $move, $capturedPieces));

        return $capturedPieces;
    }

    private function hasCaptureMove(Coordinates $coordinates)
    {
        foreach(Direction::members() as $direction) {
            if($this->hasCapturingMoveInDirection($coordinates,$direction)) {
                return true;
            }
        }

        return false;
    }

    private function hasCapturingMoveInDirection(Coordinates $coordinates, Direction $direction)
    {
        $field = $this->fields->getField($coordinates);
        if($field->isPieceAKing())
        {
            $maxRangeCoordinates = $this->getCorner($direction);
        } else {
            $maxRangeCoordinates = $coordinates;
            for($i = 0; $i < $field->getPiecesJumpRange(); ++$i) {
                $maxRangeCoordinates = $maxRangeCoordinates->next($direction);
            }
        }

        try {
            $capturedPieces = $this->findCapturedPieces(new Move($coordinates, $maxRangeCoordinates));
        } catch (IllegalMoveException $e) {
            return false;
        }

        return ($capturedPieces->current() !== null);

    }

    private function getCorner(Direction $direction)
    {
        switch($direction) {
            case Direction::SOUTHWEST(): {
                return new CartesianCoordinates(1,1);
            }
            case Direction::SOUTHEAST(): {
                return new CartesianCoordinates($this->rules->getBoardLength(),1);
            }
            case Direction::NORTHWEST(): {
                return new CartesianCoordinates(1,$this->rules->getBoardLength());
            }
            case Direction::NORTHEAST(): {
                return new CartesianCoordinates($this->rules->getBoardLength(),$this->rules->getBoardLength());
            }
        }

        throw new FieldDoesNotExistException;
    }

    /**
     * Check move legality and return pieces captured by the move
     * @param Move $move
     * @return CapturedPieces
     * @throws IllegalMoveException
     * @throws NoPieceOnFieldException
     * @throws FieldDoesNotExistException
     */
    private function checkMoveLegality(Move $move)
    {

        if(!$this->coordinatesOnBoard($move->getFrom()) ||
           !$this->coordinatesOnBoard($move->getTo()))
        {
            throw new IllegalMoveException("Move is outside board range");
        }

        $startField = $this->fields->getField($move->getFrom());
        $endField = $this->fields->getField($move->getTo());

        if($endField->hasPiece())
        {
            throw new IllegalMoveException("Occupied field - cannot move here.");
        }


        $pieces = [];
        foreach($this->findCapturedPieces($move) as $piece)
        {
            $pieces[] = $piece;
        }

        $capturedPieces = new CapturedPieces($pieces);

        $pieceIsAKing = $startField->isPieceAKing();

        if($capturedPieces->moreThanZero() || $pieceIsAKing) {
            if($pieceIsAKing) {
                if($this->rules->doKingsStopOnFieldAfterCapture()) {
                    $lastCapturedPiece = $capturedPieces->getLastPiece();
                    if ($move->getFrom()->after($lastCapturedPiece) != $move->getTo()) {
                        throw new IllegalMoveException("King has stop on field directly after captured piece.");
                    }
                }
            }

            //Move distance is the jump distance
            $maxMoveDistance = $startField->getPiecesJumpRange();
        } else {
            if(!$this->isMoveForward($move)) {
                throw new IllegalMoveException("Non-capturing normal piece move has to be forward.");
            }
            $maxMoveDistance = 1;
        }

        if($move->getDistance() > $maxMoveDistance)
        {
            throw new IllegalMoveException("Occupied field - cannot move here.");
        }

        return $capturedPieces;
    }

    /**
     * Check if under a given move we're capturing any pieces
     * @param Move $move
     * @throws IllegalMoveException If move is not legal and hence will not capture anything
     * @return \Generator Captured pieces
     */
    private function findCapturedPieces(Move $move)
    {
        $field = $this->fields->getField($move->getFrom());
        $color = $field->getPiecesColor();

        $it = $move->getIterator();
        $it->next(); //Skip our starting point

        while($it->valid())
        {
            $currentCoordinates = $it->current();

            if($this->fields->hasPiece($currentCoordinates))
            {

                $jumpedOverPieceColor = $this->fields->getField($currentCoordinates)->getPiecesColor();
                if($color->getOpposedColor() == $jumpedOverPieceColor)
                {
                    $it->next();
                    if(!$it->valid())
                    {
                        break;
                    }

                    $nextCoordinates = $it->current();
                    if($this->fields->getField($nextCoordinates)->hasPiece())
                    {
                        throw new IllegalMoveException("Cannot jump over more than one piece");
                    }
                    
                    yield new CapturedPiece($currentCoordinates, $jumpedOverPieceColor);
                }
                else
                {
                    throw new IllegalMoveException("Cannot move over same color's piece");
                }

            }

            $it->next();
        }

    }


    private function coordinatesOnBoard(Coordinates $coordinates)
    {
        return ($coordinates->getX() > 0 && $coordinates->getX() <= $this->rules->getBoardLength() &&
            $coordinates->getY() > 0 && $coordinates->getY() <= $this->rules->getBoardLength());
    }

    private function isMoveForward(Move $move)
    {
        $color = $this->fields->getField($move->getFrom())->getPiecesColor();
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
            $fields->addField(new CartesianCoordinates($startingX, $rowNumber), $field);
            $piece = new Piece($color);
            $field->putDown($piece);
        }
    }
}