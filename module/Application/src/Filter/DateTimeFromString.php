<?php

namespace Application\Filter;

use Laminas\Filter\AbstractFilter;

class DateTimeFromString extends AbstractFilter
{
    private static $instance;



    public function filter($value)
    {
        if ($value === '') return null;

        return $this->convert($value);
    }



    protected function convert($value): ?\DateTime
    {
        if (!$value) return null;

        $datetime = null;
        if (strlen($value) == 10) {
            $value .= "T00:00:00";
        } elseif (strlen($value) == 16) {
            $value .= ":00";
        }

        $datetime = \DateTime::createFromFormat('Y-m-d\TH:i:s', $value);

        if ($datetime instanceof \DateTime) return $datetime;

        return null;
    }



    public static function run($value)
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance->filter($value);
    }
}