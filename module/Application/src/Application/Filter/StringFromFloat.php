<?php

namespace Application\Filter;

use UnicaenApp\Util;
use Zend\Filter\AbstractFilter;

class StringFromFloat extends AbstractFilter
{
    private static $instance;



    public function filter($value)
    {
        return Util::formattedFloat($value);
    }



    public static function run( $value )
    {
        if (!self::$instance){
            self::$instance = new self;
        }

        return self::$instance->filter($value);
    }
}