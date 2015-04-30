<?php

namespace Application\Service\Traits;

use Application\Service\ModificationServiceDu;
use Common\Exception\RuntimeException;

trait ModificationServiceDuAwareTrait
{
    /**
     * description
     *
     * @var ModificationServiceDu
     */
    private $serviceModificationServiceDu;

    /**
     *
     * @param ModificationServiceDu $serviceModificationServiceDu
     * @return self
     */
    public function setServiceModificationServiceDu( ModificationServiceDu $serviceModificationServiceDu )
    {
        $this->serviceModificationServiceDu = $serviceModificationServiceDu;
        return $this;
    }

    /**
     *
     * @return ModificationServiceDu
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceModificationServiceDu()
    {
        if (empty($this->serviceModificationServiceDu)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationModificationServiceDu');
        }else{
            return $this->serviceModificationServiceDu;
        }
    }

}