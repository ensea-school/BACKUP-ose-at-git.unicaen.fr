<?php

namespace Enseignement\Processus;


/**
 * Description of EnseignementProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait EnseignementProcessusAwareTrait
{
    protected ?EnseignementProcessus $processusEnseignement = null;



    /**
     * @param EnseignementProcessus $processusEnseignement
     *
     * @return self
     */
    public function setProcessusEnseignement(?EnseignementProcessus $processusEnseignement)
    {
        $this->processusEnseignement = $processusEnseignement;

        return $this;
    }



    public function getProcessusEnseignement(): ?EnseignementProcessus
    {
        if (empty($this->processusEnseignement)) {
            $this->processusEnseignement = \Unicaen\Framework\Application\Application::getInstance()->container()->get(EnseignementProcessus::class);
        }

        return $this->processusEnseignement;
    }
}