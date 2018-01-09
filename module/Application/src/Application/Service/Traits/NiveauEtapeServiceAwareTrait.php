<?php

namespace Application\Service\Traits;

use Application\Service\NiveauEtapeService;

/**
 * Description of NiveauEtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauEtapeServiceAwareTrait
{
    /**
     * @var NiveauEtapeService
     */
    private $serviceNiveauEtape;



    /**
     * @param NiveauEtapeService $serviceNiveauEtape
     *
     * @return self
     */
    public function setServiceNiveauEtape(NiveauEtapeService $serviceNiveauEtape)
    {
        $this->serviceNiveauEtape = $serviceNiveauEtape;

        return $this;
    }



    /**
     * @return NiveauEtapeService
     */
    public function getServiceNiveauEtape()
    {
        if (empty($this->serviceNiveauEtape)) {
            $this->serviceNiveauEtape = \Application::$container->get(NiveauEtapeService::class);
        }

        return $this->serviceNiveauEtape;
    }
}