<?php

namespace Application\Service\Traits;

use Application\Service\AgrementNavigationPagesProvider;
use Common\Exception\RuntimeException;

trait AgrementNavigationPagesProviderAwareTrait
{
    /**
     * description
     *
     * @var AgrementNavigationPagesProvider
     */
    private $serviceAgrementNavigationPagesProvider;

    /**
     *
     * @param AgrementNavigationPagesProvider $serviceAgrementNavigationPagesProvider
     * @return self
     */
    public function setServiceAgrementNavigationPagesProvider( AgrementNavigationPagesProvider $serviceAgrementNavigationPagesProvider )
    {
        $this->serviceAgrementNavigationPagesProvider = $serviceAgrementNavigationPagesProvider;
        return $this;
    }

    /**
     *
     * @return AgrementNavigationPagesProvider
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceAgrementNavigationPagesProvider()
    {
        if (empty($this->serviceAgrementNavigationPagesProvider)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationAgrementNavigationPagesProvider');
        }else{
            return $this->serviceAgrementNavigationPagesProvider;
        }
    }

}