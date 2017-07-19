<?php

namespace Application\Service\Interfaces;

use Application\Service\CentreCout;
use RuntimeException;

/**
 * Description of CentreCoutAwareInterface
 *
 * @author UnicaenCode
 */
interface CentreCoutAwareInterface
{
    /**
     * @param CentreCout $serviceCentreCout
     * @return self
     */
    public function setServiceCentreCout( CentreCout $serviceCentreCout );



    /**
     * @return CentreCoutAwareInterface
     * @throws RuntimeException
     */
    public function getServiceCentreCout();
}