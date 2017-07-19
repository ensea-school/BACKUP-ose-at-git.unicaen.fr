<?php

namespace Application\Service\Interfaces;

use Application\Service\ServiceReferentiel;
use RuntimeException;

/**
 * Description of ServiceReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface ServiceReferentielAwareInterface
{
    /**
     * @param ServiceReferentiel $serviceServiceReferentiel
     * @return self
     */
    public function setServiceServiceReferentiel( ServiceReferentiel $serviceServiceReferentiel );



    /**
     * @return ServiceReferentielAwareInterface
     * @throws RuntimeException
     */
    public function getServiceServiceReferentiel();
}