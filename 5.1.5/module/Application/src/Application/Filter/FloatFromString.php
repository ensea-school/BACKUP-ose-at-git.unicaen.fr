<?php

namespace Application\Filter;

use Zend\Filter\AbstractFilter;

class FloatFromString extends AbstractFilter
{
    private static $instance;


    public function filter($value)
    {
        $value = preg_replace("/[^0-9,\.-]/","",$value);
        $value = str_replace(',','.',$value);
        $value = floatval($value);

        return $value;
    }



    public static function run( $value )
    {
        if (!self::$instance){
            self::$instance = new self;
        }

        return self::$instance->filter($value);
    }
}