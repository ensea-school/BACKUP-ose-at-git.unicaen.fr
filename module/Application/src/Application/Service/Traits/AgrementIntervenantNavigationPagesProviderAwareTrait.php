<?php

namespace Application\Service\Traits;

use Application\Service\AgrementIntervenantNavigationPagesProvider;
use Application\Module;
use RuntimeException;

/**
 * Description of AgrementIntervenantNavigationPagesProviderAwareTrait
 *
 * @author UnicaenCode
 */
trait AgrementIntervenantNavigationPagesProviderAwareTrait
{
    /**
     * @var AgrementIntervenantNavigationPagesProvider
     */
    private $serviceAgrementIntervenantNavigationPagesProvider;





    /**
     * @param AgrementIntervenantNavigationPagesProvider $serviceAgrementIntervenantNavigationPagesProvider
     * @return self
     */
    public function setServiceAgrementIntervenantNavigationPagesProvider( AgrementIntervenantNavigationPagesProvider $serviceAgrementIntervenantNavigationPagesProvider )
    {
        $this->serviceAgrementIntervenantNavigationPagesProvider = $serviceAgrementIntervenantNavigationPagesProvider;
        return $this;
    }



    /**
     * @return AgrementIntervenantNavigationPagesProvider
     * @throws RuntimeException
     */
    public function getServiceAgrementIntervenantNavigationPagesProvider()
    {
        if (empty($this->serviceAgrementIntervenantNavigationPagesProvider)){
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
        $this->serviceAgrementIntervenantNavigationPagesProvider = $serviceLocator->get('AgrementIntervenantNavigationPagesProvider');
        }
        return $this->serviceAgrementIntervenantNavigationPagesProvider;
    }
}