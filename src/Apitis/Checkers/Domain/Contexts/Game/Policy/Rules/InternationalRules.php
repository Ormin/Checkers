<?php
/**
 * Created by PhpStorm.
 * Date: 3/31/17
 * Time: 1:46 PM
 */

namespace Apitis\Checkers\Domain\Contexts\Game\Policy\Rules;


use Apitis\Checkers\Domain\Contexts\Game\Policy\Rules;

class InternationalRules implements Rules
{
    public function howManyPiecesPerSide()
    {
        return 20;
    }

    public function isCapturingMandatory()
    {
        return true;
    }

    public function getBoardLength()
    {
        return 10;
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