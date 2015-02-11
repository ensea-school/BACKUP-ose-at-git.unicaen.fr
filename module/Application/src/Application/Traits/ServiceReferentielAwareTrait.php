<?php

namespace Application\Traits;

use Application\Entity\Db\ServiceReferentiel;

/**
 * 
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait ServiceReferentielAwareTrait
{
    /**
     * @var ServiceReferentiel
     */
    protected $service;

    /**
     * Spécifie le service concerné.
     *
     * @param Service $service le service concerné
     */
    public function setService(ServiceReferentiel $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Retourne le service concerné.
     *
     * @return ServiceReferentiel
     */
    public function getService()
    {
        return $this->service;
    }
}