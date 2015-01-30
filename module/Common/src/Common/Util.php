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
        $negatif = $heures < 0;
        $heures = \UnicaenApp\Util::formattedFloat($heures, \NumberFormatter::DECIMAL, 2);
        $heures = str_replace( ',00', '<span style="opacity:0.3">,00</span>', $heures );
        if ($negatif){
            $heures = '<span style="color:red">'.$heures.'</span>';
        }
        return $heures;
    }

    /**
     *
     * @param float $heures
     */
    public static function formattedPourcentage( $heures )
    {
        $heures = round( (float)$heures*100, 2);
        $negatif = $heures < 0;
        $heures = \UnicaenApp\Util::formattedFloat($heures, \NumberFormatter::DECIMAL, 2);
        $heures = str_replace( ',00', '<span style="opacity:0.3">,00</span>', $heures ).'%';
        if ($negatif){
            $heures = '<span style="color:red">'.$heures.'</span>';
        }
        return $heures;
    }

}