<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\HistoIntervenantService;

/**
 * Description of HistoIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait HistoIntervenantServiceAwareTrait
{
    /**
     * @var HistoIntervenantService
     */
    private $histoIntervenantService;





    /**
     * @param HistoIntervenantService $histoIntervenantService
     * @return self
     */
    public function setHistoIntervenantService( HistoIntervenantService $histoIntervenantService = null )
    {
        $this->histoIntervenantService = $histoIntervenantService;
        return $this;
    }



    /**
     * @return HistoIntervenantService
     */
    public function getHistoIntervenantService()
    {
        return $this->histoIntervenantService;
    }
}