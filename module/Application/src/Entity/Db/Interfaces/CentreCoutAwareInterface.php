<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\CentreCout;

/**
 * Description of CentreCoutAwareInterface
 *
 * @author UnicaenCode
 */
interface CentreCoutAwareInterface
{
    /**
     * @param CentreCout $centreCout
     * @return self
     */
    public function setCentreCout( CentreCout $centreCout = null );



    /**
     * @return CentreCout
     */
    public function getCentreCout();
}