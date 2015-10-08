<?php

namespace Application\Service\Traits;

use Application\Service\AgrementNavigationPagesProvider;
use Application\Module;
use RuntimeException;

/**
 * Description of AgrementNavigationPagesProviderAwareTrait
 *
 * @author UnicaenCode
 */
trait AgrementNavigationPagesProviderAwareTrait
{
    /**
     * @var AgrementNavigationPagesProvider
     */
    private $serviceAgrementNavigationPagesProvider;





    /**
     * @param AgrementNavigationPagesProvider $serviceAgrementNavigationPagesProvider
     * @return self
     */
    public function setServiceAgrementNavigationPagesProvider( AgrementNavigationPagesProvider $serviceAgrementNavigationPagesProvider )
    {
        $this->serviceAgrementNavigationPagesProvider = $serviceAgrementNavigationPagesProvider;
        return $this;
    }



    /**
     * @return AgrementNavigationPagesProvider
     * @throws RuntimeException
     */
    public function getServiceAgrementNavigationPagesProvider()
    {
        if (empty($this->serviceAgrementNavigationPagesProvider)){
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
        $this->serviceAgrementNavigationPagesProvider = $serviceLocator->get('AgrementNavigationPagesProvider');
        }
        return $this->serviceAgrementNavigationPagesProvider;
    }
}