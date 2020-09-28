<?php

namespace Application\Service\Traits;

use Application\Service\DossierAutreTypeService;

/**
 * Description of DossierAutreTypeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DossierAutreTypeServiceAwareTrait
{
    /**
     * @var DossierAutreTypeService
     */
    private $serviceDossierAutreType;



    /**
     * @param DossierAutreTypeService $serviceDossierAutreType
     *
     * @return self
     */
    public function setServiceDossierAutreType(DossierAutreTypeService $serviceDossierAutreType)
    {
        $this->$serviceDossierAutreType = $serviceDossierAutreType;

        return $this;
    }



    /**
     * @return DossierAutreTypeService
     */
    public function getServiceDossierAutreType()
    {
        if (empty($this->serviceDossierAutreType)) {
            $this->serviceDossierAutreType = \Application::$container->get(DossierAutreTypeService::class);
        }

        return $this->serviceDossierAutreType;
    }
}