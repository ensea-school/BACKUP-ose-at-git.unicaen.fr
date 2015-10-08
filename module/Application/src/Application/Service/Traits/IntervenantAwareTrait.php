<?php

namespace Application\Service\Traits;

use Application\Service\Intervenant;
use Application\Module;
use RuntimeException;

/**
 * Description of IntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantAwareTrait
{
    /**
     * @var Intervenant
     */
    private $serviceIntervenant;





    /**
     * @param Intervenant $serviceIntervenant
     * @return self
     */
    public function setServiceIntervenant( Intervenant $serviceIntervenant )
    {
        $this->serviceIntervenant = $serviceIntervenant;
        return $this;
    }



    /**
     * @return Intervenant
     * @throws RuntimeException
     */
    public function getServiceIntervenant()
    {
        if (empty($this->serviceIntervenant)){
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
        $this->serviceIntervenant = $serviceLocator->get('ApplicationIntervenant');
        }
        return $this->serviceIntervenant;
    }
}