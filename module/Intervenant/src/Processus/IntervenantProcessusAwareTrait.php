<?php

namespace Intervenant\Processus;

/**
 * Description of IntervenantProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantProcessusAwareTrait
{
    protected ?IntervenantProcessus $processusIntervenant = null;



    /**
     * @param IntervenantProcessus $processusIntervenant
     *
     * @return self
     */
    public function setProcessusIntervenant(?IntervenantProcessus $processusIntervenant)
    {
        $this->processusIntervenant = $processusIntervenant;

        return $this;
    }



    public function getProcessusIntervenant(): ?IntervenantProcessus
    {
        if (empty($this->processusIntervenant)) {
            $this->processusIntervenant = \Unicaen\Framework\Application\Application::getInstance()->container()->get(IntervenantProcessus::class);
        }

        return $this->processusIntervenant;
    }
}