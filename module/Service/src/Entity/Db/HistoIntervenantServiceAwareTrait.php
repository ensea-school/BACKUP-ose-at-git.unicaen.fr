<?php

namespace Service\Entity\Db;

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