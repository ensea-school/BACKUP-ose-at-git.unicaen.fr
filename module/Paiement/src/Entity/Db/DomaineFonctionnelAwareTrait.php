<?php

namespace Paiement\Entity\Db;

/**
 * Description of DomaineFonctionnelAwareTrait
 *
 * @author UnicaenCode
 */
trait DomaineFonctionnelAwareTrait
{
    protected ?DomaineFonctionnel $domaineFonctionnel = null;



    /**
     * @param DomaineFonctionnel $domaineFonctionnel
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