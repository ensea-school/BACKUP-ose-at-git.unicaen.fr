<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Etape;

/**
 * Description of EtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeAwareTrait
{
    /**
     * @var Etape
     */
    private $etape;





    /**
     * @param Etape $etape
     * @return self
     */
    public function setEtape( Etape $etape = null )
    {
        $this->etape = $etape;
        return $this;
    }



    /**
     * @return Etape
     */
    public function getEtape()
    {
        return $this->etape;
    }
}