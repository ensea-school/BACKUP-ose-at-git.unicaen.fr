<?php

namespace Application\Service\Traits;

use Application\Service\FormuleService;
use Common\Exception\RuntimeException;

trait FormuleServiceAwareTrait
{
    /**
     * description
     *
     * @var FormuleService
     */
    private $serviceFormuleService;

    /**
     *
     * @param FormuleService $serviceFormuleService
     * @return self
     */
    public function setServiceFormuleService( FormuleService $serviceFormuleService )
    {
        $this->serviceFormuleService = $serviceFormuleService;
        return $this;
    }

    /**
     *
     * @return FormuleService
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleService()
    {
        if (empty($this->serviceFormuleService)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleService');
        }else{
            return $this->serviceFormuleService;
        }
    }

}