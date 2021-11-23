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
    /**
     * @var IntervenantProcessus
     */
    private $processusIntervenant;



    /**
     * @param IntervenantProcessus $processusIntervenant
     *
     * @return self
     */
    public function setProcessusIntervenant(IntervenantProcessus $processusIntervenant)
    {
        $this->processusIntervenant = $processusIntervenant;

        return $this;
    }



    /**
     * @return IntervenantProcessus
     */
    public function getProcessusIntervenant()
    {
        if (empty($this->processusIntervenant)) {
            $this->processusIntervenant = \Application::$container->get(IntervenantProcessus::class);
        }

        return $this->processusIntervenant;
    }
}