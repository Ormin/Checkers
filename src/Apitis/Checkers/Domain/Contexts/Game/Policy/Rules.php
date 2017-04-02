<?php
/**
 * Created by PhpStorm.
 * Date: 3/31/17
 * Time: 1:46 PM
 */

namespace Apitis\Checkers\Domain\Contexts\Game\Policy;


interface Rules
{

    public function isCapturingMandatory();

    public function getBoardLength();

    public function doKingsStopOnFieldAfterCapture();

    public function canPiecePromoteOnPassJump();

    public function howManyPiecesPerSide();

}