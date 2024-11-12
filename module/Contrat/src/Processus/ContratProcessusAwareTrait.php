<?php

namespace Contrat\Processus;

/**
 * Description of ContratProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratProcessusAwareTrait
{
    protected ?ContratProcessus $processusContrat = null;



    /**
     * @param ContratProcessus $processusContrat
     *
     * @return self
     */
    public function setProcessusContrat(?ContratProcessus $processusContrat)
    {
        $this->processusContrat = $processusContrat;

        return $this;
    }



    public function getProcessusContrat(): ?ContratProcessus
    {
        if (empty($this->processusContrat)) {
            $this->processusContrat = \AppAdmin::container()->get(ContratProcessus::class);
        }

        return $this->processusContrat;
    }
}