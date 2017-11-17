<?php

namespace Application\Service\Traits;

use Application\Service\TypeModulateur;

/**
 * Description of TypeModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurAwareTrait
{
    /**
     * @var TypeModulateur
     */
    private $serviceTypeModulateur;



    /**
     * @param TypeModulateur $serviceTypeModulateur
     *
     * @return self
     */
    public function setServiceTypeModulateur(TypeModulateur $serviceTypeModulateur)
    {
        $this->serviceTypeModulateur = $serviceTypeModulateur;

        return $this;
    }



    /**
     * @return TypeModulateur
     */
    public function getServiceTypeModulateur()
    {
        if (empty($this->serviceTypeModulateur)) {
            $this->serviceTypeModulateur = \Application::$container->get('ApplicationTypeModulateur');
        }

        return $this->serviceTypeModulateur;
    }
}