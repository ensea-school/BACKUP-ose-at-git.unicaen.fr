<?php

namespace Intervenant\Service;


/**
 * Description of StatutServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutServiceAwareTrait
{
    protected ?StatutService $serviceStatut = null;



    /**
     * @param StatutService $serviceStatut
     *
     * @return self
     */
    public function setServiceStatut(?StatutService $serviceStatut)
    {
        $this->serviceStatut = $serviceStatut;

        return $this;
    }



    public function getServiceStatut(): ?StatutService
    {
        if (empty($this->serviceStatut)) {
            $this->serviceStatut = \AppAdmin::container()->get(StatutService::class);
        }

        return $this->serviceStatut;
    }
}