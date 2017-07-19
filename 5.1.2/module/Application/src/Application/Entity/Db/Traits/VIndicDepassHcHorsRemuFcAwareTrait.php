<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VIndicDepassHcHorsRemuFc;

/**
 * Description of VIndicDepassHcHorsRemuFcAwareTrait
 *
 * @author UnicaenCode
 */
trait VIndicDepassHcHorsRemuFcAwareTrait
{
    /**
     * @var VIndicDepassHcHorsRemuFc
     */
    private $vIndicDepassHcHorsRemuFc;





    /**
     * @param VIndicDepassHcHorsRemuFc $vIndicDepassHcHorsRemuFc
     * @return self
     */
    public function setVIndicDepassHcHorsRemuFc( VIndicDepassHcHorsRemuFc $vIndicDepassHcHorsRemuFc = null )
    {
        $this->vIndicDepassHcHorsRemuFc = $vIndicDepassHcHorsRemuFc;
        return $this;
    }



    /**
     * @return VIndicDepassHcHorsRemuFc
     */
    public function getVIndicDepassHcHorsRemuFc()
    {
        return $this->vIndicDepassHcHorsRemuFc;
    }
}