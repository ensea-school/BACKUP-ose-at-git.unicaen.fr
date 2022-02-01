<?php

namespace Application\Service\Traits;

use Application\Service\NiveauEtapeService;

/**
 * Description of NiveauEtapeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauEtapeServiceAwareTrait
{
    protected ?NiveauEtapeService $serviceNiveauEtape;



    /**
     * @param NiveauEtapeService|null $serviceNiveauEtape
     *
     * @return self
     */
    public function setServiceNiveauEtape( ?NiveauEtapeService $serviceNiveauEtape )
    {
        $this->serviceNiveauEtape = $serviceNiveauEtape;

        return $this;
    }



    public function getServiceNiveauEtape(): ?NiveauEtapeService
    {
        if (!$this->serviceNiveauEtape){
            $this->serviceNiveauEtape = \Application::$container->get(NiveauEtapeService::class);
        }

        return $this->serviceNiveauEtape;
    }
}