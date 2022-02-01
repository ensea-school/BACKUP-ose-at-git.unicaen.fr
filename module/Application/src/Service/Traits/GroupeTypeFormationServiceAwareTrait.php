<?php

namespace Application\Service\Traits;

use Application\Service\GroupeTypeFormationService;

/**
 * Description of GroupeTypeFormationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait GroupeTypeFormationServiceAwareTrait
{
    protected ?GroupeTypeFormationService $serviceGroupeTypeFormation;



    /**
     * @param GroupeTypeFormationService|null $serviceGroupeTypeFormation
     *
     * @return self
     */
    public function setServiceGroupeTypeFormation( ?GroupeTypeFormationService $serviceGroupeTypeFormation )
    {
        $this->serviceGroupeTypeFormation = $serviceGroupeTypeFormation;

        return $this;
    }



    public function getServiceGroupeTypeFormation(): ?GroupeTypeFormationService
    {
        if (!$this->serviceGroupeTypeFormation){
            $this->serviceGroupeTypeFormation = \Application::$container->get('FormElementManager')->get(GroupeTypeFormationService::class);
        }

        return $this->serviceGroupeTypeFormation;
    }
}