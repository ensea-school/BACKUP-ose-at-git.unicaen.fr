<?php

namespace Application\Processus\Traits;

use Application\Processus\ValidationEnseignementProcessus;
use Application\Module;
use RuntimeException;

/**
 * Description of ValidationEnseignementProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationEnseignementProcessusAwareTrait
{
    /**
     * @var ValidationEnseignementProcessus
     */
    private $processusValidationEnseignement;





    /**
     * @param ValidationEnseignementProcessus $processusValidationEnseignement
     * @return self
     */
    public function setProcessusValidationEnseignement( ValidationEnseignementProcessus $processusValidationEnseignement )
    {
        $this->processusValidationEnseignement = $processusValidationEnseignement;
        return $this;
    }



    /**
     * @return ValidationEnseignementProcessus
     * @throws RuntimeException
     */
    public function getProcessusValidationEnseignement()
    {
        if (empty($this->processusValidationEnseignement)){
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
        $this->processusValidationEnseignement = $serviceLocator->get('processusValidationEnseignement');
        }
        return $this->processusValidationEnseignement;
    }
}