<?php

namespace Dossier\Service\Traits;

use Dossier\Service\DossierAutreService;

/**
 * Description of DossierAutreServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DossierAutreServiceAwareTrait
{
    protected ?DossierAutreService $serviceDossierAutre = null;



    /**
     * @param DossierAutreService $serviceDossierAutre
     *
     * @return self
     */
    public function setServiceDossierAutre(?DossierAutreService $serviceDossierAutre)
    {
        $this->serviceDossierAutre = $serviceDossierAutre;

        return $this;
    }



    public function getServiceDossierAutre(): ?DossierAutreService
    {
        if (empty($this->serviceDossierAutre)) {
            $this->serviceDossierAutre =\Unicaen\Framework\Application\Application::getInstance()->container()->get(DossierAutreService::class);
        }

        return $this->serviceDossierAutre;
    }
}