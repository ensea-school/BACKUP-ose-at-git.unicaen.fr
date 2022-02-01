<?php

namespace Application\Service\Traits;

use Application\Service\ElementModulateurService;

/**
 * Description of ElementModulateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurServiceAwareTrait
{
    protected ?ElementModulateurService $serviceElementModulateur;



    /**
     * @param ElementModulateurService|null $serviceElementModulateur
     *
     * @return self
     */
    public function setServiceElementModulateur( ?ElementModulateurService $serviceElementModulateur )
    {
        $this->serviceElementModulateur = $serviceElementModulateur;

        return $this;
    }



    public function getServiceElementModulateur(): ?ElementModulateurService
    {
        if (!$this->serviceElementModulateur){
            $this->serviceElementModulateur = \Application::$container->get(ElementModulateurService::class);
        }

        return $this->serviceElementModulateur;
    }
}