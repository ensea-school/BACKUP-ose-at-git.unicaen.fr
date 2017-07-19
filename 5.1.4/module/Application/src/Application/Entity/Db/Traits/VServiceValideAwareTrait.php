<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VServiceValide;

/**
 * Description of VServiceValideAwareTrait
 *
 * @author UnicaenCode
 */
trait VServiceValideAwareTrait
{
    /**
     * @var VServiceValide
     */
    private $vServiceValide;





    /**
     * @param VServiceValide $vServiceValide
     * @return self
     */
    public function setVServiceValide( VServiceValide $vServiceValide = null )
    {
        $this->vServiceValide = $vServiceValide;
        return $this;
    }



    /**
     * @return VServiceValide
     */
    public function getVServiceValide()
    {
        return $this->vServiceValide;
    }
}