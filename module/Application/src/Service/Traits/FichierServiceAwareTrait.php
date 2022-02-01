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
    protected ?FichierService $serviceFichier;



    /**
     * @param FichierService|null $serviceFichier
     *
     * @return self
     */
    public function setServiceFichier( ?FichierService $serviceFichier )
    {
        $this->serviceFichier = $serviceFichier;

        return $this;
    }



    public function getServiceFichier(): ?FichierService
    {
        if (!$this->serviceFichier){
            $this->serviceFichier = \Application::$container->get(FichierService::class);
        }

        return $this->serviceFichier;
    }
}