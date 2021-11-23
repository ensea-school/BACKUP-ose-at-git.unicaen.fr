<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Periode;

/**
 * Description of PeriodeAwareInterface
 *
 * @author UnicaenCode
 */
interface PeriodeAwareInterface
{
    /**
     * @param Periode $periode
     * @return self
     */
    public function setPeriode( Periode $periode = null );



    /**
     * @return Periode
     */
    public function getPeriode();
}