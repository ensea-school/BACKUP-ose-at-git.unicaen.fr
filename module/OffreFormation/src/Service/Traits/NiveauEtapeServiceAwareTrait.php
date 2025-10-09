<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\NiveauEtapeService;

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
            $this->serviceNiveauEtape = \Unicaen\Framework\Application\Application::getInstance()->container()->get(NiveauEtapeService::class);
        }

        return $this->serviceNiveauEtape;
    }
}