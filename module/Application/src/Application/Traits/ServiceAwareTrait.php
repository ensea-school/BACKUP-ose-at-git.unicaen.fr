<?php

namespace Application\Traits;

use Application\Entity\Db\Service;

/**
 * Description of ServiceAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait ServiceAwareTrait
{
    /**
     * @var Service
     */
    protected $service;

    /**
     * Spécifie le service concerné.
     *
     * @param Service $service le service concerné
     */
    public function setService(Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Retourne le service concerné.
     *
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }
}