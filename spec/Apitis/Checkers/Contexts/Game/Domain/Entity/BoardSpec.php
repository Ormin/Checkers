<?php

namespace spec\Apitis\Checkers\Contexts\Game\Domain\Entity;

use Apitis\Checkers\Contexts\Game\Domain\Collection\FieldMatrix;
use Apitis\Checkers\Contexts\Game\Domain\Entity\Board;
use Apitis\Checkers\Contexts\Game\Domain\Policy\Rules;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BoardSpec extends ObjectBehavior
{
    function it_is_initializable($boardSize, FieldMatrix $fieldMatrix, Rules $rules)
    {
        $this->beConstructedWith($boardSize, $fieldMatrix, $rules);
        $this->shouldHaveType(Board::class);
    }
}
