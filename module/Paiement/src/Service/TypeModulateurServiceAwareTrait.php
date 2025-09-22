<?php

namespace Paiement\Service;


/**
 * Description of TypeModulateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurServiceAwareTrait
{
    protected ?TypeModulateurService $serviceTypeModulateur = null;



    /**
     * @param TypeModulateurService $serviceTypeModulateur
     *
     * @return self
     */
    public function setServiceTypeModulateur(?TypeModulateurService $serviceTypeModulateur)
    {
        $this->serviceTypeModulateur = $serviceTypeModulateur;

        return $this;
    }



    public function getServiceTypeModulateur(): ?TypeModulateurService
    {
        if (empty($this->serviceTypeModulateur)) {
            $this->serviceTypeModulateur = \Framework\Application\Application::getInstance()->container()->get(TypeModulateurService::class);
        }

        return $this->serviceTypeModulateur;
    }
}