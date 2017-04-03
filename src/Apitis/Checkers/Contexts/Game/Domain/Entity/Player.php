<?php

namespace Apitis\Checkers\Contexts\Game\Domain\Entity;


use Apitis\Checkers\Contexts\Game\Domain\Collection\CapturedPieces;
use Apitis\Checkers\Contexts\Game\Domain\ValueObject\Move;
use Apitis\Checkers\Domain\Shared\Exception\NotPlayersPieceException;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\Identifiers\PlayerId;
use Apitis\Checkers\Domain\Shared\ValueObject\Color;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

class Player extends EventSourcedAggregateRoot
{

    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var Color
     */
    private $playingAsColor;

    /**
     * Player constructor.
     * @param Color $playingAsColor
     */
    public function __construct(PlayerId $playerId, Color $playingAsColor)
    {
        $this->playerId = $playerId;
        $this->playingAsColor = $playingAsColor;
    }

    /**
     * @param GameId $gameId
     * @param Board $board
     * @param Move $move
     * @return CapturedPieces
     * @throws NotPlayersPieceException
     * @throws \Apitis\Checkers\Domain\Shared\Exception\IllegalMoveException
     */
    public function move(GameId $gameId, Board $board, Move $move)
    {
        if($this->playingAsColor != $board->getPiecesColor($move->getFrom())) {
            throw new NotPlayersPieceException();
        }

        return $board->tryToMove(
            $gameId,
            $move
        );

    }

    /**
     * @return PlayerId
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @return Color
     */
    public function getPlayingAsColor()
    {
        return $this->playingAsColor;
    }

    public function getAggregateRootId()
    {
        return $this->playerId;
    }
}