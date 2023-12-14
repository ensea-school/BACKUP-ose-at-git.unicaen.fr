<?php

namespace Service\Service;

/**
 * Description of RechercheServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait RechercheServiceAwareTrait
{
    protected ?RechercheService $serviceRecherche = null;



    /**
     * @param RechercheService $serviceRecherche
     *
     * @return self
     */
    public function setServiceRecherche(?RechercheService $serviceRecherche)
    {
        $this->serviceRecherche = $serviceRecherche;

        return $this;
    }



    public function getServiceRecherche(): ?RechercheService
    {
        if (empty($this->serviceRecherche)) {
            $this->serviceRecherche = \OseAdmin::instance()->container()->get(RechercheService::class);
        }

        return $this->serviceRecherche;
    }
}