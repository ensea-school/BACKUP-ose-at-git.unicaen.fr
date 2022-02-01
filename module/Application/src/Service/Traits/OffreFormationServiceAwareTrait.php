<?php

namespace Application\Service\Traits;

use Application\Service\OffreFormationService;

/**
 * Description of OffreFormationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait OffreFormationServiceAwareTrait
{
    protected ?OffreFormationService $serviceOffreFormation;



    /**
     * @param OffreFormationService|null $serviceOffreFormation
     *
     * @return self
     */
    public function setServiceOffreFormation( ?OffreFormationService $serviceOffreFormation )
    {
        $this->serviceOffreFormation = $serviceOffreFormation;

        return $this;
    }



    public function getServiceOffreFormation(): ?OffreFormationService
    {
        if (!$this->serviceOffreFormation){
            $this->serviceOffreFormation = \Application::$container->get('FormElementManager')->get(OffreFormationService::class);
        }

        return $this->serviceOffreFormation;
    }
}