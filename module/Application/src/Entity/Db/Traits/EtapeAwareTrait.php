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
    protected ?Etape $etape = null;



    /**
     * @param Etape $etape
     *
     * @return self
     */
    public function setEtape( Etape $etape )
    {
        $this->etape = $etape;

        return $this;
    }



    public function getEtape(): ?Etape
    {
        return $this->etape;
    }
}