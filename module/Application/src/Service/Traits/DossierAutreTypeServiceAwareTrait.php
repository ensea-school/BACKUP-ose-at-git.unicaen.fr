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
    protected ?DossierAutreTypeService $serviceDossierAutreType;



    /**
     * @param DossierAutreTypeService|null $serviceDossierAutreType
     *
     * @return self
     */
    public function setServiceDossierAutreType( ?DossierAutreTypeService $serviceDossierAutreType )
    {
        $this->serviceDossierAutreType = $serviceDossierAutreType;

        return $this;
    }



    public function getServiceDossierAutreType(): ?DossierAutreTypeService
    {
        if (!$this->serviceDossierAutreType){
            $this->serviceDossierAutreType = \Application::$container->get(DossierAutreTypeService::class);
        }

        return $this->serviceDossierAutreType;
    }
}