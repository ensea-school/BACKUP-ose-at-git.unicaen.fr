<?php

namespace Application\Service\Traits;

use Application\Service\TypeModulateurService;

/**
 * Description of TypeModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurServiceAwareTrait
{
    /**
     * @var TypeModulateurService
     */
    private $serviceTypeModulateur;



    /**
     * @param TypeModulateurService $serviceTypeModulateur
     *
     * @return self
     */
    public function setServiceTypeModulateur(TypeModulateurService $serviceTypeModulateur)
    {
        $this->serviceTypeModulateur = $serviceTypeModulateur;

        return $this;
    }



    /**
     * @return TypeModulateurService
     */
    public function getServiceTypeModulateur()
    {
        if (empty($this->serviceTypeModulateur)) {
            $this->serviceTypeModulateur = \Application::$container->get(TypeModulateurService::class);
        }

        return $this->serviceTypeModulateur;
    }
}