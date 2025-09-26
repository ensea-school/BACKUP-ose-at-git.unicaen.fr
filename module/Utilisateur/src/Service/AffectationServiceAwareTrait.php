<?php

namespace Utilisateur\Service;

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
            $this->serviceAffectation = \Framework\Application\Application::getInstance()->container()->get(AffectationService::class);
        }

        return $this->serviceAffectation;
    }
}