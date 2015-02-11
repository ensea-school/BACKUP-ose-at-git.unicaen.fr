<?php

namespace Application\Interfaces;

use Application\Entity\Db\ServiceReferentiel;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface ServiceReferentielAwareInterface
{

    /**
     * Spécifie le service concerné.
     *
     * @param ServiceReferentiel $service le service concerné
     * @return self
     */
    public function setService(ServiceReferentiel $service = null);

    /**
     * Retourne le service concerné.
     *
     * @return ServiceReferentiel
     */
    public function getService();
}