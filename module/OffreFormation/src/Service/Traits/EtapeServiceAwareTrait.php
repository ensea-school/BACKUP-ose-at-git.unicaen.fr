<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\EtapeService;

/**
 * Description of EtapeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeServiceAwareTrait
{
    protected ?EtapeService $serviceEtape = null;



    /**
     * @param EtapeService $serviceEtape
     *
     * @return self
     */
    public function setServiceEtape(?EtapeService $serviceEtape)
    {
        $this->serviceEtape = $serviceEtape;

        return $this;
    }



    public function getServiceEtape(): ?EtapeService
    {
        if (empty($this->serviceEtape)) {
            $this->serviceEtape = \Unicaen\Framework\Application\Application::getInstance()->container()->get(EtapeService::class);
        }

        return $this->serviceEtape;
    }
}

