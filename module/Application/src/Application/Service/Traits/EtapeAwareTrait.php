<?php

namespace Application\Service\Traits;

use Application\Service\Etape;

/**
 * Description of EtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeAwareTrait
{
    /**
     * @var Etape
     */
    private $serviceEtape;



    /**
     * @param Etape $serviceEtape
     *
     * @return self
     */
    public function setServiceEtape(Etape $serviceEtape)
    {
        $this->serviceEtape = $serviceEtape;

        return $this;
    }



    /**
     * @return Etape
     */
    public function getServiceEtape()
    {
        if (empty($this->serviceEtape)) {
            $this->serviceEtape = \Application::$container->get('ApplicationEtape');
        }

        return $this->serviceEtape;
    }
}