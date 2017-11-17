<?php

namespace Application\Service\Traits;

use Application\Service\NiveauEtape;

/**
 * Description of NiveauEtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauEtapeAwareTrait
{
    /**
     * @var NiveauEtape
     */
    private $serviceNiveauEtape;



    /**
     * @param NiveauEtape $serviceNiveauEtape
     *
     * @return self
     */
    public function setServiceNiveauEtape(NiveauEtape $serviceNiveauEtape)
    {
        $this->serviceNiveauEtape = $serviceNiveauEtape;

        return $this;
    }



    /**
     * @return NiveauEtape
     */
    public function getServiceNiveauEtape()
    {
        if (empty($this->serviceNiveauEtape)) {
            $this->serviceNiveauEtape = \Application::$container->get('ApplicationNiveauEtape');
        }

        return $this->serviceNiveauEtape;
    }
}