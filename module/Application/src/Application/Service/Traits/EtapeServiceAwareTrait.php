<?php

namespace Application\Service\Traits;

use Application\Service\EtapeService;

/**
 * Description of EtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeServiceAwareTrait
{
    /**
     * @var EtapeService
     */
    private $serviceEtape;



    /**
     * @param EtapeService $serviceEtape
     *
     * @return self
     */
    public function setServiceEtape(EtapeService $serviceEtape)
    {
        $this->serviceEtape = $serviceEtape;

        return $this;
    }



    /**
     * @return EtapeService
     */
    public function getServiceEtape()
    {
        if (empty($this->serviceEtape)) {
            $this->serviceEtape = \Application::$container->get(EtapeService::class);
        }

        return $this->serviceEtape;
    }
}