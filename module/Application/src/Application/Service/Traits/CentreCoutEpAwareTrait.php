<?php

namespace Application\Service\Traits;

use Application\Service\CentreCoutEp;

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
    private $serviceCentreCoutEp;



    /**
     * @param CentreCoutEp $serviceCentreCoutEp
     *
     * @return self
     */
    public function setServiceCentreCoutEp(CentreCoutEp $serviceCentreCoutEp)
    {
        $this->serviceCentreCoutEp = $serviceCentreCoutEp;

        return $this;
    }



    /**
     * @return CentreCoutEp
     */
    public function getServiceCentreCoutEp()
    {
        if (empty($this->serviceCentreCoutEp)) {
            $this->serviceCentreCoutEp = \Application::$container->get('ApplicationCentreCoutEp');
        }

        return $this->serviceCentreCoutEp;
    }
}