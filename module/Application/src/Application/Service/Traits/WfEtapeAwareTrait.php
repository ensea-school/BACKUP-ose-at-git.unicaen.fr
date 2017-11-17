<?php

namespace Application\Service\Traits;

use Application\Service\WfEtape;

/**
 * Description of WfEtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait WfEtapeAwareTrait
{
    /**
     * @var WfEtape
     */
    private $serviceWfEtape;



    /**
     * @param WfEtape $serviceWfEtape
     *
     * @return self
     */
    public function setServiceWfEtape(WfEtape $serviceWfEtape)
    {
        $this->serviceWfEtape = $serviceWfEtape;

        return $this;
    }



    /**
     * @return WfEtape
     */
    public function getServiceWfEtape()
    {
        if (empty($this->serviceWfEtape)) {
            $this->serviceWfEtape = \Application::$container->get('applicationWfEtape');
        }

        return $this->serviceWfEtape;
    }
}