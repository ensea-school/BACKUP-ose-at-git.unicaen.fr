<?php

namespace Application\Traits;

use Application\Entity\Db\Periode;

/**
 * Description of PeriodeAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait PeriodeAwareTrait
{
    /**
     * @var Periode
     */
    protected $periode;

    /**
     * Spécifie la période.
     *
     * @param Periode $periode la période
     */
    public function setPeriode(Periode $periode = null)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Retourne la période.
     *
     * @return Periode
     */
    public function getPeriode()
    {
        return $this->periode;
    }
}