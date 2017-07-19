<?php

namespace Application\Service\Interfaces;

use Application\Service\Etablissement;
use RuntimeException;

/**
 * Description of EtablissementAwareInterface
 *
 * @author UnicaenCode
 */
interface EtablissementAwareInterface
{
    /**
     * @param Etablissement $serviceEtablissement
     * @return self
     */
    public function setServiceEtablissement( Etablissement $serviceEtablissement );



    /**
     * @return EtablissementAwareInterface
     * @throws RuntimeException
     */
    public function getServiceEtablissement();
}