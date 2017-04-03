<?php

namespace spec\Apitis\Checkers\Contexts\Creation\Domain\Entity;

use Apitis\Checkers\Contexts\Creation\Domain\Entity\Game;
use Apitis\Checkers\Contexts\Creation\Domain\Entity\Player;
use Apitis\Checkers\Contexts\Creation\Domain\Event\GameStarted;
use Apitis\Checkers\Contexts\Creation\Domain\Event\PlayerJoinedAGame;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\ValueObject\Color;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(GameId::create());
        $this->shouldHaveType(Game::class);
    }

    function it_accepts_a_player_to_the_table(GameId $gameId, Player $player)
    {
        $this->beConstructedWith($gameId);
        $color = $this->join($player);
        $this->getUncommittedEvents()->shouldRecordEvents(
            new PlayerJoinedAGame(
                $gameId->getWrappedObject(),
                $player->getWrappedObject(),
                $color->getWrappedObject())
        );
    }

    function it_accepts_two_players_to_table(GameId $gameId, Player $player, Player $anotherPlayer)
    {
        $this->beConstructedWith($gameId);
        $color = $this->join($player)->shouldReturn(Color::WHITE());
        $anotherColor = $this->join($anotherPlayer)->shouldReturn(Color::BLACK());


        $this->getUncommittedEvents()->shouldRecordEvents(
            new PlayerJoinedAGame(
            $gameId->getWrappedObject(),
            $player->getWrappedObject(),
            $color),
            new PlayerJoinedAGame(
                $gameId->getWrappedObject(),
                $anotherPlayer->getWrappedObject(),
                $anotherColor),
            new GameStarted(
                $gameId->getWrappedObject(),
                $player->getWrappedObject(),
                $anotherPlayer->getWrappedObject()
            )
        );
    }
}
