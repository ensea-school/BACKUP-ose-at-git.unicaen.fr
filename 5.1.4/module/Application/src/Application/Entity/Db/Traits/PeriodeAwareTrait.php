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
    /**
     * @var Periode
     */
    private $periode;





    /**
     * @param Periode $periode
     * @return self
     */
    public function setPeriode( Periode $periode = null )
    {
        $this->periode = $periode;
        return $this;
    }



    /**
     * @return Periode
     */
    public function getPeriode()
    {
        return $this->periode;
    }
}