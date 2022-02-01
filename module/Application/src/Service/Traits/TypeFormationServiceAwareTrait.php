<?php

namespace Application\Service\Traits;

use Application\Service\TypeFormationService;

/**
 * Description of TypeFormationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeFormationServiceAwareTrait
{
    protected ?TypeFormationService $serviceTypeFormation = null;



    /**
     * @param TypeFormationService $serviceTypeFormation
     *
     * @return self
     */
    public function setServiceTypeFormation( TypeFormationService $serviceTypeFormation )
    {
        $this->serviceTypeFormation = $serviceTypeFormation;

        return $this;
    }



    public function getServiceTypeFormation(): ?TypeFormationService
    {
        if (empty($this->serviceTypeFormation)){
            $this->serviceTypeFormation = \Application::$container->get(TypeFormationService::class);
        }

        return $this->serviceTypeFormation;
    }
}