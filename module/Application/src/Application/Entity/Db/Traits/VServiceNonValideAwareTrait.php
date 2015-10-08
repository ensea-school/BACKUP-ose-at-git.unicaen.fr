<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VServiceNonValide;

/**
 * Description of VServiceNonValideAwareTrait
 *
 * @author UnicaenCode
 */
trait VServiceNonValideAwareTrait
{
    /**
     * @var VServiceNonValide
     */
    private $vServiceNonValide;





    /**
     * @param VServiceNonValide $vServiceNonValide
     * @return self
     */
    public function setVServiceNonValide( VServiceNonValide $vServiceNonValide = null )
    {
        $this->vServiceNonValide = $vServiceNonValide;
        return $this;
    }



    /**
     * @return VServiceNonValide
     */
    public function getVServiceNonValide()
    {
        return $this->vServiceNonValide;
    }
}