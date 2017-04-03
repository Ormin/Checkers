<?php

namespace Apitis\Checkers\Contexts\Game\Domain\Entity;


use Apitis\Checkers\Contexts\Game\Domain\Event\GameFinished;
use Apitis\Checkers\Contexts\Game\Domain\Event\TurnEnded;
use Apitis\Checkers\Contexts\Game\Domain\Policy\Rules;
use Apitis\Checkers\Contexts\Game\Domain\ValueObject\Move;
use Apitis\Checkers\Domain\Shared\Exception\IllegalStateException;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

class Game extends EventSourcedAggregateRoot
{

    /**
     * @Column(type="integer", length=32, unique=true, nullable=false)
     */
    private $gameId;

    /**
     * @OneToOne(targetEntity="Player")
     * @JoinColumn(name="first_player", referencedColumnName="id")
     */
    private $firstPlayer;

    /**
     * @OneToOne(targetEntity="Customer")
     * @JoinColumn(name="second_player", referencedColumnName="id")
     */
    private $secondPlayer;

    /**
     * @OneToOne(targetEntity="Customer")
     * @JoinColumn(name="current_player", referencedColumnName="id")
     */
    private $currentPlayer;

    /**
     * @OneToOne(targetEntity="Board")
     * @JoinColumn(name="board_id", referencedColumnName="id")
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
        $thisTurnPlayer = $this->currentPlayer;
        $capturedPieces = $thisTurnPlayer->move($this->gameId, $this->board, $move);

        if(!$capturedPieces->moreThanZero()) {
            $this->apply(new TurnEnded($this->gameId, $thisTurnPlayer));
        }

        if(!$this->board->hasPiecesOfColorLeft($this->currentPlayer->getPlayingAsColor())) {
             $this->apply(new GameFinished($this->gameId, $thisTurnPlayer, $this->currentPlayer));
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

    protected function getChildEntities()
    {
        return [$this->board];
    }


}