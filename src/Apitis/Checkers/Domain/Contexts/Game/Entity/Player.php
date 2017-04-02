<?php

namespace Apitis\Checkers\Domain\Contexts\Game\Entity;


use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Move;
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