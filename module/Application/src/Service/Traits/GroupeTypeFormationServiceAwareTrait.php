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
    protected ?GroupeTypeFormationService $serviceGroupeTypeFormation = null;



    /**
     * @param GroupeTypeFormationService $serviceGroupeTypeFormation
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
        if (empty($this->serviceGroupeTypeFormation)){
            $this->serviceGroupeTypeFormation = \Application::$container->get(GroupeTypeFormationService::class);
        }

        return $this->serviceGroupeTypeFormation;
    }
}