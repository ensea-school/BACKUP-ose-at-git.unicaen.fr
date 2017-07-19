<?php

namespace Application\Processus\Traits;

use Application\Processus\ServiceReferentielProcessus;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setProcessusServiceReferentiel( ServiceReferentielProcessus $processusServiceReferentiel )
    {
        $this->processusServiceReferentiel = $processusServiceReferentiel;
        return $this;
    }



    /**
     * @return ServiceReferentielProcessus
     * @throws RuntimeException
     */
    public function getProcessusServiceReferentiel()
    {
        if (empty($this->processusServiceReferentiel)){
            $serviceLocator = Module::$serviceLocator;
            if (! $serviceLocator) {
                if (!method_exists($this, 'getServiceLocator')) {
                    throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
                }

                $serviceLocator = $this->getServiceLocator();
                if (method_exists($serviceLocator, 'getServiceLocator')) {
                    $serviceLocator = $serviceLocator->getServiceLocator();
                }
            }
            $this->processusServiceReferentiel = $serviceLocator->get('processusServiceReferentiel');
        }
        return $this->processusServiceReferentiel;
    }
}