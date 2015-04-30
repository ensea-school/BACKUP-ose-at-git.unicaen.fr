<?php

namespace Application\Service\Traits;

use Application\Service\AgrementIntervenantNavigationPagesProvider;
use Common\Exception\RuntimeException;

trait AgrementIntervenantNavigationPagesProviderAwareTrait
{
    /**
     * description
     *
     * @var AgrementIntervenantNavigationPagesProvider
     */
    private $serviceAgrementIntervenantNavigationPagesProvider;

    /**
     *
     * @param AgrementIntervenantNavigationPagesProvider $serviceAgrementIntervenantNavigationPagesProvider
     * @return self
     */
    public function setServiceAgrementIntervenantNavigationPagesProvider( AgrementIntervenantNavigationPagesProvider $serviceAgrementIntervenantNavigationPagesProvider )
    {
        $this->serviceAgrementIntervenantNavigationPagesProvider = $serviceAgrementIntervenantNavigationPagesProvider;
        return $this;
    }

    /**
     *
     * @return AgrementIntervenantNavigationPagesProvider
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceAgrementIntervenantNavigationPagesProvider()
    {
        if (empty($this->serviceAgrementIntervenantNavigationPagesProvider)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationAgrementIntervenantNavigationPagesProvider');
        }else{
            return $this->serviceAgrementIntervenantNavigationPagesProvider;
        }
    }

}