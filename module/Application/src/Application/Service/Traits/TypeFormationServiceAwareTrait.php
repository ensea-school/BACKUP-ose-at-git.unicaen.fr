<?php

namespace Application\Service\Traits;

use Application\Service\TypeFormationService;

/**
 * Description of TypeFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeFormationServiceAwareTrait
{
    /**
     * @var TypeFormationService
     */
    private $serviceTypeFormation;



    /**
     * @param TypeFormationService $serviceTypeFormation
     *
     * @return self
     */
    public function setServiceTypeFormation(TypeFormationService $serviceTypeFormation)
    {
        $this->serviceTypeFormation = $serviceTypeFormation;

        return $this;
    }



    /**
     * @return TypeFormationService
     */
    public function getServiceTypeFormation()
    {
        if (empty($this->serviceTypeFormation)) {
            $this->serviceTypeFormation = \Application::$container->get(TypeFormationService::class);
        }

        return $this->serviceTypeFormation;
    }
}