<?php

namespace Application\Service\Traits;

use Application\Service\Modulateur;

/**
 * Description of ModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait ModulateurAwareTrait
{
    /**
     * @var Modulateur
     */
    private $serviceModulateur;



    /**
     * @param Modulateur $serviceModulateur
     *
     * @return self
     */
    public function setServiceModulateur(Modulateur $serviceModulateur)
    {
        $this->serviceModulateur = $serviceModulateur;

        return $this;
    }



    /**
     * @return Modulateur
     */
    public function getServiceModulateur()
    {
        if (empty($this->serviceModulateur)) {
            $this->serviceModulateur = \Application::$container->get('ApplicationModulateur');
        }

        return $this->serviceModulateur;
    }
}