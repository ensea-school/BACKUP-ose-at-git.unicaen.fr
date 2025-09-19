<?php

namespace Application\Service\Traits;

use Application\Service\FichierService;

/**
 * Description of FichierServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FichierServiceAwareTrait
{
    protected ?FichierService $serviceFichier = null;



    /**
     * @param FichierService $serviceFichier
     *
     * @return self
     */
    public function setServiceFichier(?FichierService $serviceFichier)
    {
        $this->serviceFichier = $serviceFichier;

        return $this;
    }



    public function getServiceFichier(): ?FichierService
    {
        if (empty($this->serviceFichier)) {
            $this->serviceFichier = \Framework\Application\Application::getInstance()->container()->get(FichierService::class);
        }

        return $this->serviceFichier;
    }
}