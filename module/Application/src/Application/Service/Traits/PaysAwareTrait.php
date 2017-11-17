<?php

namespace Application\Service\Traits;

use Application\Service\Pays;

/**
 * Description of PaysAwareTrait
 *
 * @author UnicaenCode
 */
trait PaysAwareTrait
{
    /**
     * @var Pays
     */
    private $servicePays;



    /**
     * @param Pays $servicePays
     *
     * @return self
     */
    public function setServicePays(Pays $servicePays)
    {
        $this->servicePays = $servicePays;

        return $this;
    }



    /**
     * @return Pays
     */
    public function getServicePays()
    {
        if (empty($this->servicePays)) {
            $this->servicePays = \Application::$container->get('ApplicationPays');
        }

        return $this->servicePays;
    }
}