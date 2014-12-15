<?php

namespace Common;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Util
{

    /**
     *
     * @param float $heures
     */
    public static function formattedHeures( $heures )
    {
        $heures = round( (float)$heures, 2);
        $heures = \UnicaenApp\Util::formattedFloat($heures, \NumberFormatter::DECIMAL, 2);
        $heures = str_replace( ',00', '<span style="color:gray">,00</span>', $heures );
        return $heures;
    }

    /**
     *
     * @param float $value
     */
    public static function formattedPourcentage( $value )
    {
        $value = round( (float)$value*100, 2);
        $value = \UnicaenApp\Util::formattedFloat($value, \NumberFormatter::DECIMAL, 2);
        $value = str_replace( ',00', '<span style="color:gray">,00</span>', $value );
        return $value.'%';
    }

}