<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\DomaineFonctionnel;

/**
 * Description of DomaineFonctionnelAwareInterface
 *
 * @author UnicaenCode
 */
interface DomaineFonctionnelAwareInterface
{
    /**
     * @param DomaineFonctionnel $domaineFonctionnel
     * @return self
     */
    public function setDomaineFonctionnel( DomaineFonctionnel $domaineFonctionnel = null );



    /**
     * @return DomaineFonctionnel
     */
    public function getDomaineFonctionnel();
}