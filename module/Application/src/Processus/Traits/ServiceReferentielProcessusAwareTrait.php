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
    protected ?ServiceReferentielProcessus $processusServiceReferentiel = null;



    /**
     * @param ServiceReferentielProcessus $processusServiceReferentiel
     *
     * @return self
     */
    public function setProcessusServiceReferentiel( ServiceReferentielProcessus $processusServiceReferentiel )
    {
        $this->processusServiceReferentiel = $processusServiceReferentiel;

        return $this;
    }



    public function getProcessusServiceReferentiel(): ?ServiceReferentielProcessus
    {
        if (empty($this->processusServiceReferentiel)){
            $this->processusServiceReferentiel = \Application::$container->get(ServiceReferentielProcessus::class);
        }

        return $this->processusServiceReferentiel;
    }
}