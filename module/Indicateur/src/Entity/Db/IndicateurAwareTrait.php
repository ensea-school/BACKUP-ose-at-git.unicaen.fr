<?php

namespace Indicateur\Entity\Db;


/**
 * Description of IndicateurAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurAwareTrait
{
    protected ?Indicateur $indicateur = null;



    /**
     * @param Indicateur $indicateur
     *
     * @return self
     */
    public function setIndicateur( ?Indicateur $indicateur )
    {
        $this->indicateur = $indicateur;

        return $this;
    }



    public function getIndicateur(): ?Indicateur
    {
        return $this->indicateur;
    }
}