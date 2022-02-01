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
    protected ?DomaineFonctionnel $domaineFonctionnel;



    /**
     * @param DomaineFonctionnel|null $domaineFonctionnel
     *
     * @return self
     */
    public function setDomaineFonctionnel( ?DomaineFonctionnel $domaineFonctionnel )
    {
        $this->domaineFonctionnel = $domaineFonctionnel;

        return $this;
    }



    public function getDomaineFonctionnel(): ?DomaineFonctionnel
    {
        return $this->domaineFonctionnel;
    }
}