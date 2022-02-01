<?php

namespace Application\Service\Traits;

use Application\Service\EmployeurService;

/**
 * Description of EmployeurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait EmployeurServiceAwareTrait
{
    protected ?EmployeurService $serviceEmployeur;



    /**
     * @param EmployeurService|null $serviceEmployeur
     *
     * @return self
     */
    public function setServiceEmployeur( ?EmployeurService $serviceEmployeur )
    {
        $this->serviceEmployeur = $serviceEmployeur;

        return $this;
    }



    public function getServiceEmployeur(): ?EmployeurService
    {
        if (!$this->serviceEmployeur){
            $this->serviceEmployeur = \Application::$container->get(EmployeurService::class);
        }

        return $this->serviceEmployeur;
    }
}