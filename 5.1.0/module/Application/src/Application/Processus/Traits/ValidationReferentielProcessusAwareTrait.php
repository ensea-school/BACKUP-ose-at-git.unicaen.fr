<?php

namespace Application\Processus\Traits;

use Application\Processus\ValidationReferentielProcessus;
use Application\Module;
use RuntimeException;

/**
 * Description of ValidationReferentielProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationReferentielProcessusAwareTrait
{
    /**
     * @var ValidationReferentielProcessus
     */
    private $processusValidationReferentiel;





    /**
     * @param ValidationReferentielProcessus $processusValidationReferentiel
     * @return self
     */
    public function setProcessusValidationReferentiel( ValidationReferentielProcessus $processusValidationReferentiel )
    {
        $this->processusValidationReferentiel = $processusValidationReferentiel;
        return $this;
    }



    /**
     * @return ValidationReferentielProcessus
     * @throws RuntimeException
     */
    public function getProcessusValidationReferentiel()
    {
        if (empty($this->processusValidationReferentiel)){
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
        $this->processusValidationReferentiel = $serviceLocator->get('processusValidationReferentiel');
        }
        return $this->processusValidationReferentiel;
    }
}