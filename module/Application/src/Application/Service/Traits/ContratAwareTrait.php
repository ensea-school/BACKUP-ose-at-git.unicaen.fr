<?php

namespace Application\Service\Traits;

use Application\Service\Contrat;

/**
 * Description of ContratAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratAwareTrait
{
    /**
     * @var Contrat
     */
    private $serviceContrat;



    /**
     * @param Contrat $serviceContrat
     *
     * @return self
     */
    public function setServiceContrat(Contrat $serviceContrat)
    {
        $this->serviceContrat = $serviceContrat;

        return $this;
    }



    /**
     * @return Contrat
     * @throws RuntimeException
     */
    public function getServiceContrat()
    {
        if (empty($this->serviceContrat)) {
            $this->serviceContrat = \Application::$container->get('ApplicationContrat');
        }

        return $this->serviceContrat;
    }
}