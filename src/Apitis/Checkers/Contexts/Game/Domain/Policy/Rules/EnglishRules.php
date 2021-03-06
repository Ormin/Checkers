<?php
/**
 * Created by PhpStorm.
 * Date: 3/31/17
 * Time: 1:46 PM
 */

namespace Apitis\Checkers\Contexts\Game\Domain\Policy\Rules;


use Apitis\Checkers\Contexts\Game\Domain\Policy\Rules;

class EnglishRules implements Rules
{
    public function howManyPiecesPerSide()
    {
        return 12;
    }

    public function isCapturingMandatory()
    {
        return true;
    }

    public function getBoardLength()
    {
        return 8;
    }

    public function doKingsStopOnFieldAfterCapture()
    {
        return false;
    }

    public function canPiecePromoteOnPassJump()
    {
        return false;
    }


}