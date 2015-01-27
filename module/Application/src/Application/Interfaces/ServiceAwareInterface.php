<?php

namespace Application\Interfaces;

use Application\Entity\Db\Service;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface ServiceAwareInterface
{

    /**
     * Spécifie le service concerné.
     *
     * @param Service $service le service concerné
     * @return self
     */
    public function setService(Service $service = null);

    /**
     * Retourne le service concerné.
     *
     * @return Service
     */
    public function getService();
}