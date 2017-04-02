<?php

namespace Apitis\Checkers\Domain\Shared\ValueObject;


use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class Color
 * @method static Color WHITE()
 * @method static Color BLACK()
 * @package Apitis\Checkers\Domain\Shared\ValueObject
 */
class Color extends AbstractEnumeration
{
    
    const WHITE = 'White';

    const BLACK = 'Black';

    public function getOpposedColor()
    {
        switch($this) {
            case Color::WHITE(): {
                return Color::BLACK();
            }

            case Color::BLACK(): {
                return Color::WHITE();
            }
        }
    }

}