<?php

namespace Application\Service\Traits;

use Application\Service\DossierAutreService;

/**
 * Description of DossierAutreServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DossierAutreServiceAwareTrait
{
    /**
     * @var DossierAutreService
     */
    private $serviceDossierAutre;



    /**
     * @param DossierAutreService $serviceDossierAutre
     *
     * @return self
     */
    public function setServiceDossierAutre(DossierAutreService $serviceDossierAutre)
    {
        $this->$serviceDossierAutre = $serviceDossierAutre;

        return $this;
    }



    /**
     * @return DossierAutreService
     */
    public function getServiceDossierAutre()
    {
        if (empty($this->serviceDossierAutre)) {
            $this->serviceDossierAutre = \Application::$container->get(DossierAutreService::class);
        }

        return $this->serviceDossierAutre;
    }
}