<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\CentreCoutEp;

/**
 * Description of CentreCoutEpAwareInterface
 *
 * @author UnicaenCode
 */
interface CentreCoutEpAwareInterface
{
    /**
     * @param CentreCoutEp $centreCoutEp
     * @return self
     */
    public function setCentreCoutEp( CentreCoutEp $centreCoutEp = null );



    /**
     * @return CentreCoutEp
     */
    public function getCentreCoutEp();
}