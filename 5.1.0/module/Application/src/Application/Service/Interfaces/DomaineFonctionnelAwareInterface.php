<?php

namespace Application\Service\Interfaces;

use Application\Service\DomaineFonctionnel;
use RuntimeException;

/**
 * Description of DomaineFonctionnelAwareInterface
 *
 * @author UnicaenCode
 */
interface DomaineFonctionnelAwareInterface
{
    /**
     * @param DomaineFonctionnel $serviceDomaineFonctionnel
     * @return self
     */
    public function setServiceDomaineFonctionnel( DomaineFonctionnel $serviceDomaineFonctionnel );



    /**
     * @return DomaineFonctionnelAwareInterface
     * @throws RuntimeException
     */
    public function getServiceDomaineFonctionnel();
}