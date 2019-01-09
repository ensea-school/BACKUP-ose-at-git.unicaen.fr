<?php

namespace Application\Service\Traits;

use Application\Service\NiveauFormationService;

/**
 * Description of NiveauFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauFormationServiceAwareTrait
{
    /**
     * @var NiveauFormationService
     */
    private $serviceNiveauFormation;



    /**
     * @param NiveauFormationService $serviceNiveauFormation
     *
     * @return self
     */
    public function setServiceNiveauFormation(NiveauFormationService $serviceNiveauFormation)
    {
        $this->serviceNiveauFormation = $serviceNiveauFormation;

        return $this;
    }



    /**
     * @return NiveauFormationService
     */
    public function getServiceNiveauFormation()
    {
        if (empty($this->serviceNiveauFormation)) {
            $this->serviceNiveauFormation = \Application::$container->get(NiveauFormationService::class);
        }

        return $this->serviceNiveauFormation;
    }
}