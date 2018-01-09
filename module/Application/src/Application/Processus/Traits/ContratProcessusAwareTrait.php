<?php

namespace Application\Processus\Traits;

use Application\Processus\ContratProcessus;

/**
 * Description of ContratProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratProcessusAwareTrait
{
    /**
     * @var ContratProcessus
     */
    private $processusContrat;



    /**
     * @param ContratProcessus $processusContrat
     *
     * @return self
     */
    public function setProcessusContrat(ContratProcessus $processusContrat)
    {
        $this->processusContrat = $processusContrat;

        return $this;
    }



    /**
     * @return ContratProcessus
     */
    public function getProcessusContrat()
    {
        if (empty($this->processusContrat)) {
            $this->processusContrat = \Application::$container->get(ContratProcessus::class);
        }

        return $this->processusContrat;
    }
}