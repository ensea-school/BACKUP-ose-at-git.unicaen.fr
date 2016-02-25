<?php

namespace Application\Filter;

use UnicaenApp\Util;
use Zend\Filter\AbstractFilter;

class StringFromFloat extends AbstractFilter
{
    private static $instance;



    public function filter($value, $show0Digits=true)
    {
        $res = Util::formattedFloat($value);
        if (! $show0Digits){
            $res = str_replace( ',00', '', $res );
        }
        return $res;
    }



    public static function run( $value, $show0Digits=true )
    {
        if (!self::$instance){
            self::$instance = new self;
        }

        return self::$instance->filter($value, $show0Digits);
    }
}