<?php
/**
 * Created by PhpStorm.
 * Date: 4/2/17
 * Time: 7:38 PM
 */

namespace Apitis\Checkers\Domain\Contexts\Game\Collection;


use Apitis\Checkers\Domain\Contexts\Game\ValueObject\CapturedPiece;

class CapturedPieces implements \IteratorAggregate
{

    /**
     * @var CapturedPiece[]
     */
    private $pieces;

    /**
     * CapturedPieces constructor.
     * @param CapturedPiece[] $pieces
     */
    public function __construct(array $pieces)
    {
        $this->pieces = $pieces;
    }

    /**
     * @return CapturedPiece[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->pieces);
    }

    /**
     * Gets last piece taken out by the capture
     * @return CapturedPiece
     */
    public function getLastPiece()
    {
        return $this->pieces[count($this->pieces)-1];
    }

    public function moreThanZero()
    {
        return count($this->pieces) > 0;
    }

}