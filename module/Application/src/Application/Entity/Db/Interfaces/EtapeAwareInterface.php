<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Etape;

/**
 * Description of EtapeAwareInterface
 *
 * @author UnicaenCode
 */
interface EtapeAwareInterface
{
    /**
     * @param Etape $etape
     * @return self
     */
    public function setEtape( Etape $etape = null );



    /**
     * @return Etape
     */
    public function getEtape();
}