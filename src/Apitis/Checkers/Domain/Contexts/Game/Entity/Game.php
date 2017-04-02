<?php

namespace Apitis\Checkers\Domain\Contexts\Game\Entity;


use Apitis\Checkers\Domain\Contexts\Game\Event\TurnEnded;
use Apitis\Checkers\Domain\Contexts\Game\Policy\Rules;
use Apitis\Checkers\Domain\Contexts\Game\ValueObject\Move;
use Apitis\Checkers\Domain\Shared\Exception\IllegalStateException;
use Apitis\Checkers\Domain\Shared\Exception\WrongPlayersTurnException;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

class Game extends EventSourcedAggregateRoot
{

    /**
     * @var GameId
     */
    private $gameId;

    /**
     * @var Player
     */
    private $firstPlayer;

    /**
     * @var Player
     */
    private $secondPlayer;

    /**
     * @var Player
     */
    private $currentPlayer;

    /**
     * @var Board
     */
    private $board;

    /**
     * Construct a game.
     * Current player provided has be either the whites player or the blacks player
     * @param GameId $gameId
     * @param Player $firstPlayer
     * @param Player $secondPlayer
     * @param Player $currentPlayer
     * @param Board $board
     * @throws IllegalStateException
     */
    public function __construct(GameId $gameId,
                                Player $firstPlayer,
                                Player $secondPlayer,
                                Player $currentPlayer,
                                Board $board)
    {
        $this->gameId = $gameId;
        
        if($firstPlayer === $secondPlayer) {
            throw new IllegalStateException("One player cannot both play black and whites.");
        }

        if($currentPlayer != $firstPlayer && $currentPlayer != $secondPlayer) {
            throw new IllegalStateException("Current game player must be one of the game players.");
        }

        $this->firstPlayer = $firstPlayer;
        $this->secondPlayer = $secondPlayer;
        $this->currentPlayer = $currentPlayer;
        $this->board = $board;
    }

    public function getAggregateRootId()
    {
        return $this->gameId;
    }

    public function performMove(Move $move)
    {
        $capturedPieces = $this->currentPlayer->move($this->gameId, $this->board, $move);

        if(!$capturedPieces->moreThanZero()) {
            $this->apply(new TurnEnded($this->gameId, $this->currentPlayer));
        }
    }

    protected function applyTurnEndedEvent(TurnEnded $event)
    {
        if($this->currentPlayer === $this->firstPlayer) {
            $newCurrentPlayer = $this->secondPlayer;
        } else {
            $newCurrentPlayer = $this->firstPlayer;
        }

        $this->currentPlayer = $newCurrentPlayer;
    }

}