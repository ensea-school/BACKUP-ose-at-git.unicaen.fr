<?php

namespace Application\Entity\Db\Traits;

use Service\Entity\Db\HistoIntervenantService;

/**
 * Description of HistoIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait HistoIntervenantServiceAwareTrait
{
    protected ?HistoIntervenantService $histoIntervenantService = null;



    /**
     * @param HistoIntervenantService $histoIntervenantService
     *
     * @return self
     */
    public function setHistoIntervenantService(?HistoIntervenantService $histoIntervenantService)
    {
        $this->histoIntervenantService = $histoIntervenantService;

        return $this;
    }



    public function getHistoIntervenantService(): ?HistoIntervenantService
    {
        return $this->histoIntervenantService;
    }
}