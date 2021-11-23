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
    /**
     * @var ServiceReferentielProcessus
     */
    private $processusServiceReferentiel;



    /**
     * @param ServiceReferentielProcessus $processusServiceReferentiel
     *
     * @return self
     */
    public function setProcessusServiceReferentiel(ServiceReferentielProcessus $processusServiceReferentiel)
    {
        $this->processusServiceReferentiel = $processusServiceReferentiel;

        return $this;
    }



    /**
     * @return ServiceReferentielProcessus
     */
    public function getProcessusServiceReferentiel()
    {
        if (empty($this->processusServiceReferentiel)) {
            $this->processusServiceReferentiel = \Application::$container->get(ServiceReferentielProcessus::class);
        }

        return $this->processusServiceReferentiel;
    }
}