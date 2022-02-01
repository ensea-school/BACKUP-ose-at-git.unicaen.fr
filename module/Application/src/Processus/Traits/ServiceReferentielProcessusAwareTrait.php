<?php

namespace Application\Processus\Traits;

use Application\Processus\ServiceReferentielProcessus;

/**
 * Description of ServiceReferentielProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceReferentielProcessusAwareTrait
{
    protected ?ServiceReferentielProcessus $processusServiceReferentiel;



    /**
     * @param ServiceReferentielProcessus|null $processusServiceReferentiel
     *
     * @return self
     */
    public function setProcessusServiceReferentiel( ?ServiceReferentielProcessus $processusServiceReferentiel )
    {
        $this->processusServiceReferentiel = $processusServiceReferentiel;

        return $this;
    }



    public function getProcessusServiceReferentiel(): ?ServiceReferentielProcessus
    {
        if (!$this->processusServiceReferentiel){
            $this->processusServiceReferentiel = \Application::$container->get(ServiceReferentielProcessus::class);
        }

        return $this->processusServiceReferentiel;
    }
}