<?php

namespace Application\Service\Traits;

use Application\Service\Affectation;
use Application\Module;
use RuntimeException;

/**
 * Description of AffectationAwareTrait
 *
 * @author UnicaenCode
 */
trait AffectationAwareTrait
{
    /**
     * @var Affectation
     */
    private $serviceAffectation;





    /**
     * @param Affectation $serviceAffectation
     * @return self
     */
    public function setServiceAffectation( Affectation $serviceAffectation )
    {
        $this->serviceAffectation = $serviceAffectation;
        return $this;
    }



    /**
     * @return Affectation
     * @throws RuntimeException
     */
    public function getServiceAffectation()
    {
        if (empty($this->serviceAffectation)){
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
        $this->serviceAffectation = $serviceLocator->get('ApplicationAffectation');
        }
        return $this->serviceAffectation;
    }
}