<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\DomaineFonctionnel;

/**
 * Description of DomaineFonctionnelAwareTrait
 *
 * @author UnicaenCode
 */
trait DomaineFonctionnelAwareTrait
{
    /**
     * @var DomaineFonctionnel
     */
    private $domaineFonctionnel;





    /**
     * @param DomaineFonctionnel $domaineFonctionnel
     * @return self
     */
    public function setDomaineFonctionnel( DomaineFonctionnel $domaineFonctionnel = null )
    {
        $this->domaineFonctionnel = $domaineFonctionnel;
        return $this;
    }



    /**
     * @return DomaineFonctionnel
     */
    public function getDomaineFonctionnel()
    {
        return $this->domaineFonctionnel;
    }
}