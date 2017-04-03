<?php

namespace spec\Apitis\Checkers\Contexts\Game\Domain\Entity;

use Apitis\Checkers\Contexts\Game\Domain\Event\TurnEnded;
use Apitis\Checkers\Contexts\Game\Domain\Collection\CapturedPieces;
use Apitis\Checkers\Contexts\Game\Domain\Entity\Board;
use Apitis\Checkers\Contexts\Game\Domain\Entity\Game;
use Apitis\Checkers\Contexts\Game\Domain\Entity\Player;
use Apitis\Checkers\Contexts\Game\Domain\ValueObject\Move;
use Apitis\Checkers\Domain\Shared\Identifiers\GameId;
use Apitis\Checkers\Domain\Shared\ValueObject\Color;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GameSpec extends ObjectBehavior
{
    function it_is_initializable(GameId $gameId, Player $player, Player $anotherPlayer, Board $board)
    {
        $this->beConstructedWith($gameId, $player, $anotherPlayer, $player, $board);
        $this->shouldHaveType(Game::class);
    }

    function it_ends_turn_once_no_pieces_were_captured(GameId $gameId,
                                                       Player $player,
                                                       Player $anotherPlayer,
                                                       Board $board,
                                                       Move $move,
                                                       CapturedPieces $capturedPieces,
                                                       Color $color)
    {
        //var_dump(get_declared_classes());
        //exit();

        $this->beConstructedWith($gameId, $player, $anotherPlayer, $player, $board);
        $player->move($gameId, $board, $move)->willReturn($capturedPieces);
        $capturedPieces->moreThanZero()->willReturn(false);
        $player->getPlayingAsColor()->willReturn($color);
        $color->getOpposedColor()->willReturn($color);
        $board->hasPiecesOfColorLeft($color)->willReturn(true);
        $this->performMove($move);
        $this->getUncommittedEvents()->shouldRecordEvents(
            new TurnEnded($gameId->getWrappedObject(), $player->getWrappedObject())
        );
    }


    function it_wont_turn_if_pieces_were_captured(GameId $gameId,
                                                       Player $player,
                                                       Player $anotherPlayer,
                                                       Board $board,
                                                       Move $move,
                                                       CapturedPieces $capturedPieces,
                                                       Color $color

    )
    {
        //var_dump(get_declared_classes());
        //exit();

        $this->beConstructedWith($gameId, $player, $anotherPlayer, $player, $board);
        $player->move($gameId, $board, $move)->willReturn($capturedPieces);
        $player->getPlayingAsColor()->willReturn($color);
        $color->getOpposedColor()->willReturn($color);
        $board->hasPiecesOfColorLeft($color)->willReturn(true);
        $capturedPieces->moreThanZero()->willReturn(true);
        $this->performMove($move);
        $this->getUncommittedEvents()->shouldRecordEvents(/*nothing*/);
    }

}
