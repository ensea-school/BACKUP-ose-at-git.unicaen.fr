<?php

namespace Dossier\Service\Traits;

use Dossier\Service\EmployeurService;

/**
 * Description of EmployeurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait EmployeurServiceAwareTrait
{
    protected ?EmployeurService $serviceEmployeur = null;



    /**
     * @param EmployeurService $serviceEmployeur
     *
     * @return self
     */
    public function setServiceEmployeur(?EmployeurService $serviceEmployeur)
    {
        $this->serviceEmployeur = $serviceEmployeur;

        return $this;
    }



    public function getServiceEmployeur(): ?EmployeurService
    {
        if (empty($this->serviceEmployeur)) {
            $this->serviceEmployeur = \Framework\Application\Application::getInstance()->container()->get(EmployeurService::class);
        }

        return $this->serviceEmployeur;
    }
}