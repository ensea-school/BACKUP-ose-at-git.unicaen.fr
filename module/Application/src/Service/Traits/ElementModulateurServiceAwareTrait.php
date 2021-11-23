<?php

namespace Application\Service\Traits;

use Application\Service\ElementModulateurService;

/**
 * Description of ElementModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurServiceAwareTrait
{
    /**
     * @var ElementModulateurService
     */
    private $serviceElementModulateur;



    /**
     * @param ElementModulateurService $serviceElementModulateur
     *
     * @return self
     */
    public function setServiceElementModulateur(ElementModulateurService $serviceElementModulateur)
    {
        $this->serviceElementModulateur = $serviceElementModulateur;

        return $this;
    }



    /**
     * @return ElementModulateurService
     */
    public function getServiceElementModulateur()
    {
        if (empty($this->serviceElementModulateur)) {
            $this->serviceElementModulateur = \Application::$container->get(ElementModulateurService::class);
        }

        return $this->serviceElementModulateur;
    }
}