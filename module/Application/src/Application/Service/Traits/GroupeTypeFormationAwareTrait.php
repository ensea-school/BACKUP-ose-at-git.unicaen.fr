<?php

namespace Application\Service\Traits;

use Application\Service\GroupeTypeFormation;
use Application\Module;
use RuntimeException;

/**
 * Description of GroupeTypeFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait GroupeTypeFormationAwareTrait
{
    /**
     * @var GroupeTypeFormation
     */
    private $serviceGroupeTypeFormation;





    /**
     * @param GroupeTypeFormation $serviceGroupeTypeFormation
     * @return self
     */
    public function setServiceGroupeTypeFormation( GroupeTypeFormation $serviceGroupeTypeFormation )
    {
        $this->serviceGroupeTypeFormation = $serviceGroupeTypeFormation;
        return $this;
    }



    /**
     * @return GroupeTypeFormation
     * @throws RuntimeException
     */
    public function getServiceGroupeTypeFormation()
    {
        if (empty($this->serviceGroupeTypeFormation)){
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
        $this->serviceGroupeTypeFormation = $serviceLocator->get('ApplicationGroupeTypeFormation');
        }
        return $this->serviceGroupeTypeFormation;
    }
}