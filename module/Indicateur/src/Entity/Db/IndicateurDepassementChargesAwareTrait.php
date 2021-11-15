<?php

namespace Indicateur\Entity\Db;

/**
 * Description of IndicateurDepassementChargesAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurDepassementChargesAwareTrait
{
    /**
     * @var IndicateurDepassementCharges
     */
    protected $indicateurDepassementCharges;



    /**
     * @param IndicateurDepassementCharges $indicateurDepassementCharges
     *
     * @return self
     */
    public function setIndicateurDepassementCharges(IndicateurDepassementCharges $indicateurDepassementCharges = null)
    {
        $this->indicateurDepassementCharges = $indicateurDepassementCharges;

        return $this;
    }



    /**
     * @return IndicateurDepassementCharges
     */
    public function getIndicateurDepassementCharges()
    {
        return $this->indicateurDepassementCharges;
    }
}