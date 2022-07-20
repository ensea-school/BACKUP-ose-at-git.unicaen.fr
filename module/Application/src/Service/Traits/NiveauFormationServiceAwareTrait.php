<?php

namespace Application\Service\Traits;

use Application\Service\NiveauFormationService;

/**
 * Description of NiveauFormationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauFormationServiceAwareTrait
{
    protected ?NiveauFormationService $serviceNiveauFormation = null;



    /**
     * @param NiveauFormationService $serviceNiveauFormation
     *
     * @return self
     */
    public function setServiceNiveauFormation(?NiveauFormationService $serviceNiveauFormation)
    {
        $this->serviceNiveauFormation = $serviceNiveauFormation;

        return $this;
    }



    public function getServiceNiveauFormation(): ?NiveauFormationService
    {
        if (empty($this->serviceNiveauFormation)) {
            $this->serviceNiveauFormation = \Application::$container->get(NiveauFormationService::class);
        }

        return $this->serviceNiveauFormation;
    }
}