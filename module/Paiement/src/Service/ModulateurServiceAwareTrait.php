<?php

namespace Paiement\Service;


/**
 * Description of ModulateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ModulateurServiceAwareTrait
{
    protected ?ModulateurService $serviceModulateur = null;



    /**
     * @param ModulateurService $serviceModulateur
     *
     * @return self
     */
    public function setServiceModulateur(?ModulateurService $serviceModulateur)
    {
        $this->serviceModulateur = $serviceModulateur;

        return $this;
    }



    public function getServiceModulateur(): ?ModulateurService
    {
        if (empty($this->serviceModulateur)) {
            $this->serviceModulateur = \Framework\Application\Application::getInstance()->container()->get(ModulateurService::class);
        }

        return $this->serviceModulateur;
    }
}