<?php

namespace Apitis\Checkers\Domain\Shared\ValueObject;


use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class Direction
 * @method static Direction NORTHEAST()
 * @method static Direction NORTHWEST()
 * @method static Direction SOUTHEAST()
 * @method static Direction SOUTHWEST()
 * @package Apitis\Checkers\Domain\Shared\ValueObject
 */
class Direction extends AbstractEnumeration
{

    const NORTHEAST = 1;
    const NORTHWEST = 2;
    const SOUTHEAST = 3;
    const SOUTHWEST = 4;


}