<?php

namespace Application\Interfaces;

use Application\Entity\Db\Periode;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface PeriodeAwareInterface
{

    /**
     * Spécifie la période.
     *
     * @param Periode $periode la période
     * @return self
     */
    public function setPeriode(Periode $periode = null);

    /**
     * Retourne la période.
     *
     * @return Periode
     */
    public function getPeriode();
}