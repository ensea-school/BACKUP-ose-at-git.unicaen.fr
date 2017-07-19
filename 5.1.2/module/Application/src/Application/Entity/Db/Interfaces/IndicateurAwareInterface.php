<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Indicateur;

/**
 * Description of IndicateurAwareInterface
 *
 * @author UnicaenCode
 */
interface IndicateurAwareInterface
{
    /**
     * @param Indicateur $indicateur
     * @return self
     */
    public function setIndicateur( Indicateur $indicateur = null );



    /**
     * @return Indicateur
     */
    public function getIndicateur();
}