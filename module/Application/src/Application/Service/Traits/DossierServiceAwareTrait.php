<?php

namespace Application\Service\Traits;

use Application\Service\DossierService;

/**
 * Description of DossierAwareTrait
 *
 * @author UnicaenCode
 */
trait DossierServiceAwareTrait
{
    /**
     * @var DossierService
     */
    private $serviceDossier;



    /**
     * @param DossierService $serviceDossier
     *
     * @return self
     */
    public function setServiceDossier(DossierService $serviceDossier)
    {
        $this->serviceDossier = $serviceDossier;

        return $this;
    }



    /**
     * @return DossierService
     */
    public function getServiceDossier()
    {
        if (empty($this->serviceDossier)) {
            $this->serviceDossier = \Application::$container->get(DossierService::class);
        }

        return $this->serviceDossier;
    }
}