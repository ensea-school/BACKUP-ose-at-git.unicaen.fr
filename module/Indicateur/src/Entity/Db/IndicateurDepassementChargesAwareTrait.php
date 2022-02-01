<?php

namespace Indicateur\Entity\Db;


/**
 * Description of IndicateurDepassementChargesAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurDepassementChargesAwareTrait
{
    protected ?IndicateurDepassementCharges $indicateurDepassementCharges;



    /**
     * @param IndicateurDepassementCharges|null $indicateurDepassementCharges
     *
     * @return self
     */
    public function setIndicateurDepassementCharges( ?IndicateurDepassementCharges $indicateurDepassementCharges )
    {
        $this->indicateurDepassementCharges = $indicateurDepassementCharges;

        return $this;
    }



    public function getIndicateurDepassementCharges(): ?IndicateurDepassementCharges
    {
        return $this->indicateurDepassementCharges;
    }
}