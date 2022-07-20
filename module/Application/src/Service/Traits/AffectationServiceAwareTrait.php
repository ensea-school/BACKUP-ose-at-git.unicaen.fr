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
    protected ?AffectationService $serviceAffectation = null;



    /**
     * @param AffectationService $serviceAffectation
     *
     * @return self
     */
    public function setServiceAffectation(?AffectationService $serviceAffectation)
    {
        $this->serviceAffectation = $serviceAffectation;

        return $this;
    }



    public function getServiceAffectation(): ?AffectationService
    {
        if (empty($this->serviceAffectation)) {
            $this->serviceAffectation = \Application::$container->get(AffectationService::class);
        }

        return $this->serviceAffectation;
    }
}