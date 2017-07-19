<?php

namespace Application\Service\Traits;

use Application\Service\Source;
use Application\Module;
use RuntimeException;

/**
 * Description of SourceAwareTrait
 *
 * @author UnicaenCode
 */
trait SourceAwareTrait
{
    /**
     * @var Source
     */
    private $serviceSource;





    /**
     * @param Source $serviceSource
     * @return self
     */
    public function setServiceSource( Source $serviceSource )
    {
        $this->serviceSource = $serviceSource;
        return $this;
    }



    /**
     * @return Source
     * @throws RuntimeException
     */
    public function getServiceSource()
    {
        if (empty($this->serviceSource)){
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
        $this->serviceSource = $serviceLocator->get('ApplicationSource');
        }
        return $this->serviceSource;
    }
}