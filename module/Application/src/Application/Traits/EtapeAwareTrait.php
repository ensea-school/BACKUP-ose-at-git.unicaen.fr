<?php

namespace Application\Traits;

use Application\Entity\Db\Etape;

/**
 * Description of EtapeAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait EtapeAwareTrait
{
    /**
     * @var Etape
     */
    protected $etape;

    /**
     * Spécifie l'étape concernée.
     *
     * @param Etape $etape l'étape concernée
     */
    public function setEtape(Etape $etape = null)
    {
        $this->etape = $etape;

        return $this;
    }

    /**
     * Retourne l'étape concernée.
     *
     * @return Etape
     */
    public function getEtape()
    {
        return $this->etape;
    }
}