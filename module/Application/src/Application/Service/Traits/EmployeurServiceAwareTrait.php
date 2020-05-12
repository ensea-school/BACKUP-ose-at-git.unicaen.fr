<?php

namespace Application\Service\Traits;

use Application\Service\EmployeurService;

/**
 * Description of EmployeurAwareTrait
 *
 * @author UnicaenCode
 */
trait EmployeurServiceAwareTrait
{
    /**
     * @var EmployeurService
     */
    private $serviceEmployeur;

    /**
     * @param EmployeurService $serviceEmployeur
     *
     * @return self
     */
    public function setServiceEmployeur(EmployeurService $serviceEmployeur)
    {
        $this->serviceEmployeur = $serviceEmployeur;

        return $this;
    }

    /**
     * @return EmployeurService
     */
    public function getServiceEmployeur()
    {
        if(empty($this->serviceEmployeur))
        {
            $this->serviceEmployeur = \Application::$container->get(EmployeurService::class);
        }

        return $this->serviceEmployeur;
    }
}