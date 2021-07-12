<?php

namespace Indicateur\Entity\Db;

/**
 * Description of IndicateurAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurAwareTrait
{
    /**
     * @var Indicateur
     */
    private $indicateur;



    /**
     * @param Indicateur $indicateur
     *
     * @return self
     */
    public function setIndicateur(Indicateur $indicateur = null)
    {
        $this->indicateur = $indicateur;

        return $this;
    }



    /**
     * @return Indicateur
     */
    public function getIndicateur()
    {
        return $this->indicateur;
    }
}