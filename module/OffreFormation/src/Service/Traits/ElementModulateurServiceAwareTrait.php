<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\ElementModulateurService;

/**
 * Description of ElementModulateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurServiceAwareTrait
{
    protected ?ElementModulateurService $serviceElementModulateur = null;



    /**
     * @param ElementModulateurService $serviceElementModulateur
     *
     * @return self
     */
    public function setServiceElementModulateur(?ElementModulateurService $serviceElementModulateur)
    {
        $this->serviceElementModulateur = $serviceElementModulateur;

        return $this;
    }



    public function getServiceElementModulateur(): ?ElementModulateurService
    {
        if (empty($this->serviceElementModulateur)) {
            $this->serviceElementModulateur = \Unicaen\Framework\Application\Application::getInstance()->container()->get(ElementModulateurService::class);
        }

        return $this->serviceElementModulateur;
    }
}