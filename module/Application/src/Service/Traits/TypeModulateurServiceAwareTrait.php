<?php

namespace Application\Service\Traits;

use Application\Service\TypeModulateurService;

/**
 * Description of TypeModulateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurServiceAwareTrait
{
    protected ?TypeModulateurService $serviceTypeModulateur;



    /**
     * @param TypeModulateurService|null $serviceTypeModulateur
     *
     * @return self
     */
    public function setServiceTypeModulateur( ?TypeModulateurService $serviceTypeModulateur )
    {
        $this->serviceTypeModulateur = $serviceTypeModulateur;

        return $this;
    }



    public function getServiceTypeModulateur(): ?TypeModulateurService
    {
        if (!$this->serviceTypeModulateur){
            $this->serviceTypeModulateur = \Application::$container->get(TypeModulateurService::class);
        }

        return $this->serviceTypeModulateur;
    }
}