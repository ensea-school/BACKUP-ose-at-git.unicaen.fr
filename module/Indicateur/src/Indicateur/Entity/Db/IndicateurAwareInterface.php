<?php

namespace Indicateur\Entity\Db;

/**
 * Description of IndicateurAwareInterface
 *
 * @author UnicaenCode
 */
interface IndicateurAwareInterface
{
    /**
     * @param Indicateur $indicateur
     *
     * @return self
     */
    public function setIndicateur(Indicateur $indicateur = null);



    /**
     * @return Indicateur
     */
    public function getIndicateur();
}