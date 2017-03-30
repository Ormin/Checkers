<?php

namespace Apitis\Checkers\Domain\Contexts\Creation\Entity;


use Apitis\Checkers\Domain\Contexts\Creation\Event\GameCreated;
use Apitis\Checkers\Domain\Contexts\Creation\Event\GameStarted;
use Apitis\Checkers\Domain\Contexts\Creation\Event\PlayerJoinedAGame;
use Apitis\Checkers\Domain\Shared\Exception\GameIsFullException;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\Exception\IllegalStateException;
use Apitis\Checkers\Domain\Shared\ValueObject\Color;
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

    /**
     * A game can be created using a mandatory identificator, and optional players joining the whites and blacks sides.
     * @param GameId $gameId
     * @param Player|null $whitesPlayer
     * @param Player|null $blacksPlayer
     */
    public function __construct(GameId $gameId, Player $whitesPlayer = null, Player $blacksPlayer = null)
    {
        $this->gameId = $gameId;
        $this->whitesPlayer = $whitesPlayer;
        $this->blacksPlayer = $blacksPlayer;
    }

    public static function create()
    {
        $gameId = GameId::create();
        $game = new Game($gameId);
        $game->apply(new GameCreated($gameId));
        return $game;
    }

    public function getAggregateRootId()
    {
        return $this->gameId;
    }


    public function join(Player $player)
    {
        if($this->blacksPlayer !== null)
        {
            if($this->whitesPlayer !== null) {
                throw new GameIsFullException();
            } else {
                $this->joinAsWhite($player);
            }
        } else {

            if($this->whitesPlayer !== null) {
                $this->joinAsBlack($player);
            }

            if(rand(1,100) <= 50) {
                $this->joinAsBlack($player);
            } else {
                $this->joinAsWhite($player);
            }
        }

    }

    private function joinAsWhite(Player $player)
    {
        if($this->blacksPlayer === $player)
        {
            throw new IllegalStateException("One player cannot both play black and whites.");
        }

        if($this->whitesPlayer !== null)
        {
            throw new IllegalStateException("Whites player already joined.");
        }

        $this->apply(new PlayerJoinedAGame($this->gameId, $player, Color::WHITE()));
        $this->tryToStart();
    }

    private function joinAsBlack(Player $player)
    {
        if($this->whitesPlayer === $player)
        {
            throw new IllegalStateException("One player cannot both play black and whites.");
        }

        if($this->blacksPlayer !== null)
        {
            throw new IllegalStateException("Blacks player already joined.");
        }

        $this->apply(new PlayerJoinedAGame($this->gameId, $player, Color::BLACK()));
        $this->tryToStart();

    }

    private function tryToStart()
    {
        if($this->blacksPlayer !== null && $this->whitesPlayer !== null)
        {
            $this->apply(new GameStarted($this->gameId, $this->whitesPlayer, $this->blacksPlayer));
        }
    }

    protected function applyPlayerJoinedAGame(PlayerJoinedAGame $event)
    {
        switch($event->getColor()) {
            case Color::WHITE(): {
                $this->whitesPlayer = $event->getPlayer();
                break;
            }
            case Color::BLACK(): {
                $this->blacksPlayer = $event->getPlayer();
                break;
            }
        }
    }

}