<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\HistoIntervenantService;

/**
 * Description of HistoIntervenantServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface HistoIntervenantServiceAwareInterface
{
    /**
     * @param HistoIntervenantService $histoIntervenantService
     * @return self
     */
    public function setHistoIntervenantService( HistoIntervenantService $histoIntervenantService = null );



    /**
     * @return HistoIntervenantService
     */
    public function getHistoIntervenantService();
}