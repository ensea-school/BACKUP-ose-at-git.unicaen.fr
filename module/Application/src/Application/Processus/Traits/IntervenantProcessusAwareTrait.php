<?php

namespace Application\Processus\Traits;

use Application\Processus\IntervenantProcessus;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setProcessusIntervenant( IntervenantProcessus $processusIntervenant )
    {
        $this->processusIntervenant = $processusIntervenant;
        return $this;
    }



    /**
     * @return IntervenantProcessus
     * @throws RuntimeException
     */
    public function getProcessusIntervenant()
    {
        if (empty($this->processusIntervenant)){
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
            $this->processusIntervenant = $serviceLocator->get(IntervenantProcessus::class);
        }
        return $this->processusIntervenant;
    }
}