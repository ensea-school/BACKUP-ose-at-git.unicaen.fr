<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\NiveauFormationService;

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
            $this->serviceNiveauFormation = \Unicaen\Framework\Application\Application::getInstance()->container()->get(NiveauFormationService::class);
        }

        return $this->serviceNiveauFormation;
    }
}