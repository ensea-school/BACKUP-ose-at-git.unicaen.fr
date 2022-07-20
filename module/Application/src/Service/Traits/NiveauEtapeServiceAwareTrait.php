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
    protected ?NiveauEtapeService $serviceNiveauEtape = null;



    /**
     * @param NiveauEtapeService $serviceNiveauEtape
     *
     * @return self
     */
    public function setServiceNiveauEtape(?NiveauEtapeService $serviceNiveauEtape)
    {
        $this->serviceNiveauEtape = $serviceNiveauEtape;

        return $this;
    }



    public function getServiceNiveauEtape(): ?NiveauEtapeService
    {
        if (empty($this->serviceNiveauEtape)) {
            $this->serviceNiveauEtape = \Application::$container->get(NiveauEtapeService::class);
        }

        return $this->serviceNiveauEtape;
    }
}