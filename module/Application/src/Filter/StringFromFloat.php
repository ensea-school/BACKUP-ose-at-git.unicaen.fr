<?php

namespace Application\Filter;

use UnicaenApp\Util;
use Laminas\Filter\AbstractFilter;

class StringFromFloat extends AbstractFilter
{
    private static $instance;

    private        $fractions = [
        ['f' => 0.333333, 's' => '1/3'],
        ['f' => 0.166667, 's' => '1/6'],
        ['f' => 0.142857, 's' => '1/7'],
        ['f' => 0.111111, 's' => '1/9'],
        ['f' => 0.666667, 's' => '2/3'],
        ['f' => 0.285714, 's' => '2/7'],
        ['f' => 0.222222, 's' => '2/9'],
        ['f' => 0.428571, 's' => '3/7'],
        ['f' => 1.333333, 's' => '4/3'],
        ['f' => 0.571429, 's' => '4/7'],
        ['f' => 0.444444, 's' => '4/9'],
        ['f' => 1.666667, 's' => '5/3'],
        ['f' => 0.833333, 's' => '5/6'],
        ['f' => 0.714286, 's' => '5/7'],
        ['f' => 0.555556, 's' => '5/9'],
        ['f' => 0.857143, 's' => '6/7'],
        ['f' => 2.333333, 's' => '7/3'],
        ['f' => 1.166667, 's' => '7/6'],
        ['f' => 0.777778, 's' => '7/9'],
        ['f' => 2.666667, 's' => '8/3'],
        ['f' => 1.142857, 's' => '8/7'],
        ['f' => 0.888889, 's' => '8/9'],
        ['f' => 1.285714, 's' => '9/7'],
    ];



    public function filter($value, $show0Digits = true)
    {
        if ($value === null) return '';
        $valfrac = round($value, 6);
        foreach ($this->fractions as $fs) {
            if ($fs['f'] === $valfrac) {
                return $fs['s'];
            }
        }

        $res = Util::formattedFloat($value);
        if (!$show0Digits) {
            $res = str_replace(',00', '', $res ?? '');
        }

        return $res;
    }



    public static function run($value, $show0Digits = true)
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance->filter($value, $show0Digits);
    }
}