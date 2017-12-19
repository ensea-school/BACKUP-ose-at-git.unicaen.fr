<?php

namespace Application\Service\Traits;

use Application\Service\CentreCout;

/**
 * Description of CentreCoutServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutServiceAwareTrait
{
    /**
     * @var CentreCout
     */
    private $serviceCentreCout;



    /**
     * @param CentreCout $serviceCentreCout
     *
     * @return self
     */
    public function setServiceCentreCout(CentreCout $serviceCentreCout)
    {
        $this->serviceCentreCout = $serviceCentreCout;

        return $this;
    }



    /**
     * @return CentreCout
     */
    public function getServiceCentreCout()
    {
        if (empty($this->serviceCentreCout)) {
            $this->serviceCentreCout = \Application::$container->get('ApplicationCentreCout');
        }

        return $this->serviceCentreCout;
    }
}