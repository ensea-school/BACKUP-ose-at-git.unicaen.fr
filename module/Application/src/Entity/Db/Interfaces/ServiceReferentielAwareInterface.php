<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\ServiceReferentiel;

/**
 * Description of ServiceReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface ServiceReferentielAwareInterface
{
    /**
     * @param ServiceReferentiel $serviceReferentiel
     * @return self
     */
    public function setServiceReferentiel( ServiceReferentiel $serviceReferentiel = null );



    /**
     * @return ServiceReferentiel
     */
    public function getServiceReferentiel();
}