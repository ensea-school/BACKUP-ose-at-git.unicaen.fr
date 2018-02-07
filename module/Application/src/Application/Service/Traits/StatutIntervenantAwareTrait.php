<?php

namespace Application\Service\Traits;

use Application\Service\StatutIntervenant;
use Application\Module;
use RuntimeException;

/**
 * Description of StatutIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutIntervenantAwareTrait
{
    /**
     * @var StatutIntervenant
     */
    private $serviceStatutIntervenant;





    /**
     * @param StatutIntervenant $statutIntervenant
     * @return self
     */
    public function setServiceStatutIntervenant( StatutIntervenant $statutIntervenant )
    {
        $this->serviceStatutIntervenant = $statutIntervenant;
        return $this;
    }


    /**
     * @return StatutIntervenant
     * @throws RuntimeException
     */
    public function getServiceStatutIntervenant()
    {
        if (empty($this->serviceStatutIntervenant)){
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
        $this->serviceStatutIntervenant = $serviceLocator->get('ApplicationStatutIntervenant');
        }
        return $this->serviceStatutIntervenant;
    }
}