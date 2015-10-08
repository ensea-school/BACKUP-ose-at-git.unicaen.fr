<?php

namespace Application\Service\Traits;

use Application\Service\Parametres;
use Application\Module;
use RuntimeException;

/**
 * Description of ParametresAwareTrait
 *
 * @author UnicaenCode
 */
trait ParametresAwareTrait
{
    /**
     * @var Parametres
     */
    private $serviceParametres;





    /**
     * @param Parametres $serviceParametres
     * @return self
     */
    public function setServiceParametres( Parametres $serviceParametres )
    {
        $this->serviceParametres = $serviceParametres;
        return $this;
    }



    /**
     * @return Parametres
     * @throws RuntimeException
     */
    public function getServiceParametres()
    {
        if (empty($this->serviceParametres)){
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
        $this->serviceParametres = $serviceLocator->get('ApplicationParametres');
        }
        return $this->serviceParametres;
    }
}