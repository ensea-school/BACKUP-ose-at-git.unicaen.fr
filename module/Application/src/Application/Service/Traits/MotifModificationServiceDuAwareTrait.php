<?php

namespace Application\Service\Traits;

use Application\Service\MotifModificationServiceDu;
use Common\Exception\RuntimeException;

trait MotifModificationServiceDuAwareTrait
{
    /**
     * description
     *
     * @var MotifModificationServiceDu
     */
    private $serviceMotifModificationServiceDu;

    /**
     *
     * @param MotifModificationServiceDu $serviceMotifModificationServiceDu
     * @return self
     */
    public function setServiceMotifModificationServiceDu( MotifModificationServiceDu $serviceMotifModificationServiceDu )
    {
        $this->serviceMotifModificationServiceDu = $serviceMotifModificationServiceDu;
        return $this;
    }

    /**
     *
     * @return MotifModificationServiceDu
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceMotifModificationServiceDu()
    {
        if (empty($this->serviceMotifModificationServiceDu)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationMotifModificationServiceDu');
        }else{
            return $this->serviceMotifModificationServiceDu;
        }
    }

}