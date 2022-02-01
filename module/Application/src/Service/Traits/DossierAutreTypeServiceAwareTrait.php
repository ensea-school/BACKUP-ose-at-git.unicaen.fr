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
    protected ?DossierAutreTypeService $serviceDossierAutreType = null;



    /**
     * @param DossierAutreTypeService $serviceDossierAutreType
     *
     * @return self
     */
    public function setServiceDossierAutreType( DossierAutreTypeService $serviceDossierAutreType )
    {
        $this->serviceDossierAutreType = $serviceDossierAutreType;

        return $this;
    }



    public function getServiceDossierAutreType(): ?DossierAutreTypeService
    {
        if (empty($this->serviceDossierAutreType)){
            $this->serviceDossierAutreType = \Application::$container->get(DossierAutreTypeService::class);
        }

        return $this->serviceDossierAutreType;
    }
}