<?php

namespace Application\Service\Traits;

use Application\Service\CentreCoutService;

/**
 * Description of CentreCoutServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutServiceAwareTrait
{
    /**
     * @var CentreCoutService
     */
    private $serviceCentreCout;



    /**
     * @param CentreCoutService $serviceCentreCout
     *
     * @return self
     */
    public function setServiceCentreCout(CentreCoutService $serviceCentreCout)
    {
        $this->serviceCentreCout = $serviceCentreCout;

        return $this;
    }



    /**
     * @return CentreCoutService
     */
    public function getServiceCentreCout()
    {
        if (empty($this->serviceCentreCout)) {
            $this->serviceCentreCout = \Application::$container->get('ApplicationCentreCout');
        }

        return $this->serviceCentreCout;
    }
}