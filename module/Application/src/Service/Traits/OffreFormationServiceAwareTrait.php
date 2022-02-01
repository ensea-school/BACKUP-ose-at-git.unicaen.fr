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
    protected ?OffreFormationService $serviceOffreFormation = null;



    /**
     * @param OffreFormationService $serviceOffreFormation
     *
     * @return self
     */
    public function setServiceOffreFormation( OffreFormationService $serviceOffreFormation )
    {
        $this->serviceOffreFormation = $serviceOffreFormation;

        return $this;
    }



    public function getServiceOffreFormation(): ?OffreFormationService
    {
        if (empty($this->serviceOffreFormation)){
            $this->serviceOffreFormation = \Application::$container->get(OffreFormationService::class);
        }

        return $this->serviceOffreFormation;
    }
}