<?php

namespace Application\Service\Traits;


use Application\Service\OffreFormationService;

trait OffreFormationServiceAwareTrait
{
    /**
     * @var OffreFormationService
     */
    private $serviceOffreFormation;



    /**
     * @param ServiceOffreFormation $offreFormation
     *
     * @return self
     */
    public function setServiceOffreFormation(OffreFormationService $serviceOffreFormation)
    {
        $this->serviceOffreFormation = $serviceOffreFormation;

        return $this;
    }



    /**
     * @return OffreFormationService
     */
    public function getServiceOffreFormation()
    {
        if (empty($this->serviceOffreFormation)) {
            $this->serviceOffreFormation = \Application::$container->get(OffreFormationService::class);
        }

        return $this->serviceOffreFormation;
    }

}