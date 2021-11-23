<?php

namespace Application\Service\Traits;

use Application\Service\ModulateurService;

/**
 * Description of ModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait ModulateurServiceAwareTrait
{
    /**
     * @var ModulateurService
     */
    private $serviceModulateur;



    /**
     * @param ModulateurService $serviceModulateur
     *
     * @return self
     */
    public function setServiceModulateur(ModulateurService $serviceModulateur)
    {
        $this->serviceModulateur = $serviceModulateur;

        return $this;
    }



    /**
     * @return ModulateurService
     */
    public function getServiceModulateur()
    {
        if (empty($this->serviceModulateur)) {
            $this->serviceModulateur = \Application::$container->get(ModulateurService::class);
        }

        return $this->serviceModulateur;
    }
}