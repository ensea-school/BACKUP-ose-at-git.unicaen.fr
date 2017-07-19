<?php

namespace Application\Service\Interfaces;

use Application\Service\CentreCoutEp;
use RuntimeException;

/**
 * Description of CentreCoutEpAwareInterface
 *
 * @author UnicaenCode
 */
interface CentreCoutEpAwareInterface
{
    /**
     * @param CentreCoutEp $serviceCentreCoutEp
     * @return self
     */
    public function setServiceCentreCoutEp( CentreCoutEp $serviceCentreCoutEp );



    /**
     * @return CentreCoutEpAwareInterface
     * @throws RuntimeException
     */
    public function getServiceCentreCoutEp();
}