<?php

namespace Application\Filter;

use Laminas\Filter\AbstractFilter;

class FloatFromString extends AbstractFilter
{
    private static $instance;



    public function filter($value)
    {
        if ($value === '' || $value === null) return null;

        if (false !== strpos($value, '/')) {
            [$f1, $f2] = explode('/', $value);

            return $this->convert($f1) / $this->convert($f2);
        } else {
            return $this->convert($value);
        }
    }



    protected function convert($value): float
    {
        $value = preg_replace("/[^0-9,\.-]/", "", $value);
        $value = str_replace(',', '.', $value);
        $value = floatval($value);

        return $value;
    }



    public static function run($value)
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance->filter($value);
    }
}