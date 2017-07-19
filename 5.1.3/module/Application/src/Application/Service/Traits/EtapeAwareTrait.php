<?php

namespace Application\Service\Traits;

use Application\Service\Etape;
use Application\Module;
use RuntimeException;

/**
 * Description of EtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeAwareTrait
{
    /**
     * @var Etape
     */
    private $serviceEtape;





    /**
     * @param Etape $serviceEtape
     * @return self
     */
    public function setServiceEtape( Etape $serviceEtape )
    {
        $this->serviceEtape = $serviceEtape;
        return $this;
    }



    /**
     * @return Etape
     * @throws RuntimeException
     */
    public function getServiceEtape()
    {
        if (empty($this->serviceEtape)){
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
        $this->serviceEtape = $serviceLocator->get('ApplicationEtape');
        }
        return $this->serviceEtape;
    }
}