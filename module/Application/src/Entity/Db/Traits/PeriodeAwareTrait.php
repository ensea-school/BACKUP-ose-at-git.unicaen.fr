<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Periode;

/**
 * Description of PeriodeAwareTrait
 *
 * @author UnicaenCode
 */
trait PeriodeAwareTrait
{
    protected ?Periode $periode = null;



    /**
     * @param Periode $periode
     *
     * @return self
     */
    public function setPeriode( Periode $periode )
    {
        $this->periode = $periode;

        return $this;
    }



    public function getPeriode(): ?Periode
    {
        return $this->periode;
    }
}