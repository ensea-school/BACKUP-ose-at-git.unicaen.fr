<?php

namespace Application\Processus\Traits;

use Application\Processus\ValidationProcessus;
use Application\Module;
use RuntimeException;

/**
 * Description of ValidationProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationProcessusAwareTrait
{
    /**
     * @var ValidationProcessus
     */
    private $processusValidation;





    /**
     * @param ValidationProcessus $processusValidation
     * @return self
     */
    public function setProcessusValidation( ValidationProcessus $processusValidation )
    {
        $this->processusValidation = $processusValidation;
        return $this;
    }



    /**
     * @return ValidationProcessus
     * @throws RuntimeException
     */
    public function getProcessusValidation()
    {
        if (empty($this->processusValidation)){
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
            $this->processusValidation = $serviceLocator->get('processusValidation');
        }
        return $this->processusValidation;
    }
}