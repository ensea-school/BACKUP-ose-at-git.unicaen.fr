<?php

namespace Contrat\Service;


/**
 * Description of ContratServiceListeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratServiceListeServiceAwareTrait
{
    protected ?ContratServiceListeService $serviceContratServiceListe = null;



    /**
     * @param ContratServiceListeService $serviceContratServiceListe
     *
     * @return self
     */
    public function setServiceContratServiceListe(?ContratServiceListeService $serviceContratServiceListe)
    {
        $this->serviceContratServiceListe = $serviceContratServiceListe;

        return $this;
    }



    public function getServiceContratServiceListe(): ?ContratServiceListeService
    {
        if (empty($this->serviceContratServiceListe)) {
            $this->serviceContratServiceListe = \Framework\Application\Application::getInstance()->container()->get(ContratServiceListeService::class);
        }

        return $this->serviceContratServiceListe;
    }
}

