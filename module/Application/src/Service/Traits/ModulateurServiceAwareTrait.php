<?php

namespace Application\Service\Traits;

use Application\Service\ModulateurService;

/**
 * Description of ModulateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ModulateurServiceAwareTrait
{
    protected ?ModulateurService $serviceModulateur;



    /**
     * @param ModulateurService|null $serviceModulateur
     *
     * @return self
     */
    public function setServiceModulateur( ?ModulateurService $serviceModulateur )
    {
        $this->serviceModulateur = $serviceModulateur;

        return $this;
    }



    public function getServiceModulateur(): ?ModulateurService
    {
        if (!$this->serviceModulateur){
            $this->serviceModulateur = \Application::$container->get(ModulateurService::class);
        }

        return $this->serviceModulateur;
    }
}