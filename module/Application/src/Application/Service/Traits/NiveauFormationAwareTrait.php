<?php

namespace Application\Service\Traits;

use Application\Service\NiveauFormation;
use Application\Module;
use RuntimeException;

/**
 * Description of NiveauFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauFormationAwareTrait
{
    /**
     * @var NiveauFormation
     */
    private $serviceNiveauFormation;





    /**
     * @param NiveauFormation $serviceNiveauFormation
     * @return self
     */
    public function setServiceNiveauFormation( NiveauFormation $serviceNiveauFormation )
    {
        $this->serviceNiveauFormation = $serviceNiveauFormation;
        return $this;
    }



    /**
     * @return NiveauFormation
     * @throws RuntimeException
     */
    public function getServiceNiveauFormation()
    {
        if (empty($this->serviceNiveauFormation)){
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
        $this->serviceNiveauFormation = $serviceLocator->get('ApplicationNiveauFormation');
        }
        return $this->serviceNiveauFormation;
    }
}