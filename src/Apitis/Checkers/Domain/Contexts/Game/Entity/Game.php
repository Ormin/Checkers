<?php

namespace Apitis\Checkers\Domain\Contexts\Game\Entity;


use Apitis\Checkers\Domain\Contexts\Game\Entity\Identifiers\GameId;
use Apitis\Checkers\Domain\Contexts\Game\Event\GameStarted;
use Apitis\Checkers\Domain\Contexts\Game\Exception\IllegalStateException;
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
    private $whitesPlayer;

    /**
     * @var Player
     */
    private $blacksPlayer;
    
    public function __construct(GameId $gameId, Player $whitesPlayer, Player $blacksPlayer)
    {
        $this->gameId = $gameId;
        
        if($whitesPlayer === $blacksPlayer) {
            throw new IllegalStateException("One player cannot both play black and whites.");
        }
        
        $this->whitesPlayer = $whitesPlayer;
        $this->blacksPlayer = $blacksPlayer;
    }

    public static function create(Player $playerOne, Player $playerTwo)
    {
        $whitesPlayer = (rand(1,100) <= 50) ? $playerOne : $playerTwo;
        if($whitesPlayer === $playerOne) {
            $blacksPlayer = $playerTwo;
        } else {
            $blacksPlayer = $playerOne;
        }

        $gameId = GameId::create();
        $game = new Game($gameId, $whitesPlayer, $blacksPlayer);
        $game->apply(new GameStarted($gameId, $whitesPlayer, $blacksPlayer));
    }

    public function getAggregateRootId()
    {
        return $this->gameId;
    }


}