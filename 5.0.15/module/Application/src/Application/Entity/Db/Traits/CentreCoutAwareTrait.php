<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CentreCout;

/**
 * Description of CentreCoutAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutAwareTrait
{
    /**
     * @var CentreCout
     */
    private $centreCout;





    /**
     * @param CentreCout $centreCout
     * @return self
     */
    public function setCentreCout( CentreCout $centreCout = null )
    {
        $this->centreCout = $centreCout;
        return $this;
    }



    /**
     * @return CentreCout
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }
}