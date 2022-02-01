<?php

namespace Application\Processus\Traits;

use Application\Processus\IntervenantProcessus;

/**
 * Description of IntervenantProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantProcessusAwareTrait
{
    protected ?IntervenantProcessus $processusIntervenant;



    /**
     * @param IntervenantProcessus|null $processusIntervenant
     *
     * @return self
     */
    public function setProcessusIntervenant( ?IntervenantProcessus $processusIntervenant )
    {
        $this->processusIntervenant = $processusIntervenant;

        return $this;
    }



    public function getProcessusIntervenant(): ?IntervenantProcessus
    {
        if (!$this->processusIntervenant){
            $this->processusIntervenant = \Application::$container->get(IntervenantProcessus::class);
        }

        return $this->processusIntervenant;
    }
}