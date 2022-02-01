<?php

namespace Application\Processus\Traits;

use Application\Processus\ReconductionProcessus;

/**
 * Description of ReconductionProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ReconductionProcessusAwareTrait
{
    protected ?ReconductionProcessus $processusReconduction = null;



    /**
     * @param ReconductionProcessus $processusReconduction
     *
     * @return self
     */
    public function setProcessusReconduction( ReconductionProcessus $processusReconduction )
    {
        $this->processusReconduction = $processusReconduction;

        return $this;
    }



    public function getProcessusReconduction(): ?ReconductionProcessus
    {
        if (empty($this->processusReconduction)){
            $this->processusReconduction = \Application::$container->get(ReconductionProcessus::class);
        }

        return $this->processusReconduction;
    }
}