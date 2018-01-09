<?php

namespace Application\Service\Traits;

use Application\Service\GroupeTypeFormationService;

/**
 * Description of GroupeTypeFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait GroupeTypeFormationServiceAwareTrait
{
    /**
     * @var GroupeTypeFormationService
     */
    private $serviceGroupeTypeFormation;



    /**
     * @param GroupeTypeFormationService $serviceGroupeTypeFormation
     *
     * @return self
     */
    public function setServiceGroupeTypeFormation(GroupeTypeFormationService $serviceGroupeTypeFormation)
    {
        $this->serviceGroupeTypeFormation = $serviceGroupeTypeFormation;

        return $this;
    }



    /**
     * @return GroupeTypeFormationService
     */
    public function getServiceGroupeTypeFormation()
    {
        if (empty($this->serviceGroupeTypeFormation)) {
            $this->serviceGroupeTypeFormation = \Application::$container->get(GroupeTypeFormationService::class);
        }

        return $this->serviceGroupeTypeFormation;
    }
}