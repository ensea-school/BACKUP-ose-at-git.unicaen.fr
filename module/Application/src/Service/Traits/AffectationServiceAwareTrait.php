<?php

namespace Application\Service\Traits;

use Application\Service\AffectationService;

/**
 * Description of AffectationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait AffectationServiceAwareTrait
{
    /**
     * @var AffectationService
     */
    private $serviceAffectation;



    /**
     * @param AffectationService $serviceAffectation
     *
     * @return self
     */
    public function setServiceAffectation(AffectationService $serviceAffectation)
    {
        $this->serviceAffectation = $serviceAffectation;

        return $this;
    }



    /**
     * @return AffectationService
     */
    public function getServiceAffectation()
    {
        if (empty($this->serviceAffectation)) {
            $this->serviceAffectation = \Application::$container->get(AffectationService::class);
        }

        return $this->serviceAffectation;
    }
}