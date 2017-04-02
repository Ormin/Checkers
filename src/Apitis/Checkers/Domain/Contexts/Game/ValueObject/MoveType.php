<?php
/**
 * Created by PhpStorm.
 * Date: 3/31/17
 * Time: 6:09 PM
 */

namespace Apitis\Checkers\Domain\Contexts\Game\ValueObject;


use Apitis\Checkers\Domain\Shared\Exception\IllegalMoveException;
use Apitis\Checkers\Domain\Shared\ValueObject\Coordinates;
use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class Color
 * @method static MoveType NORMAL()
 * @method static MoveType CAPTURE()
 * @package Apitis\Checkers\Domain\Shared\ValueObject
 */
class MoveType extends AbstractEnumeration
{

    const NORMAL = 'Normal';

    const CAPTURE = 'Capture';
    
}