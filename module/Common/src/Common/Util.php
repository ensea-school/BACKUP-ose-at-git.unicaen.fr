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
        $class = $heures < 0 ? 'negatif' : 'positif';
        $heures = \UnicaenApp\Util::formattedFloat($heures, \NumberFormatter::DECIMAL, 2);
        $heures = str_replace( ',00', '<span class="heures-dec-00">,00</span>', $heures );
        $heures = '<span class="heures heures-'.$class.'">'.$heures.'</span>';
        return $heures;
    }

    /**
     *
     * @param float $heures
     */
    public static function formattedPourcentage( $heures )
    {
        $heures = round( (float)$heures*100, 2);
        $class = $heures < 0 ? 'negatif' : 'positif';
        $heures = \UnicaenApp\Util::formattedFloat($heures, \NumberFormatter::DECIMAL, 2);
        $heures = str_replace( ',00', '<span class="heures-dec-00">,00</span>', $heures ).'%';
        $heures = '<span class="heures heures-'.$class.'">'.$heures.'</span>';
        return $heures;
    }

}