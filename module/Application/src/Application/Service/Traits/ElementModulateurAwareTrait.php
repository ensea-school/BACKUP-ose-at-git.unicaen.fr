<?php

namespace Application\Service\Traits;

use Application\Service\ElementModulateur;

/**
 * Description of ElementModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurAwareTrait
{
    /**
     * @var ElementModulateur
     */
    private $serviceElementModulateur;



    /**
     * @param ElementModulateur $serviceElementModulateur
     *
     * @return self
     */
    public function setServiceElementModulateur(ElementModulateur $serviceElementModulateur)
    {
        $this->serviceElementModulateur = $serviceElementModulateur;

        return $this;
    }



    /**
     * @return ElementModulateur
     */
    public function getServiceElementModulateur()
    {
        if (empty($this->serviceElementModulateur)) {
            $this->serviceElementModulateur = \Application::$container->get('ApplicationElementModulateur');
        }

        return $this->serviceElementModulateur;
    }
}