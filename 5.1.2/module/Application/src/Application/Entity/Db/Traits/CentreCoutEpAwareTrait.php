<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CentreCoutEp;

/**
 * Description of CentreCoutEpAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutEpAwareTrait
{
    /**
     * @var CentreCoutEp
     */
    private $centreCoutEp;





    /**
     * @param CentreCoutEp $centreCoutEp
     * @return self
     */
    public function setCentreCoutEp( CentreCoutEp $centreCoutEp = null )
    {
        $this->centreCoutEp = $centreCoutEp;
        return $this;
    }



    /**
     * @return CentreCoutEp
     */
    public function getCentreCoutEp()
    {
        return $this->centreCoutEp;
    }
}