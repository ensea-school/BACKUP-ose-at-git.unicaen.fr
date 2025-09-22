<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\GroupeTypeFormationService;

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
    public function setServiceGroupeTypeFormation(?GroupeTypeFormationService $serviceGroupeTypeFormation)
    {
        $this->serviceGroupeTypeFormation = $serviceGroupeTypeFormation;

        return $this;
    }



    public function getServiceGroupeTypeFormation(): ?GroupeTypeFormationService
    {
        if (empty($this->serviceGroupeTypeFormation)) {
            $this->serviceGroupeTypeFormation = \Framework\Application\Application::getInstance()->container()->get(GroupeTypeFormationService::class);
        }

        return $this->serviceGroupeTypeFormation;
    }
}