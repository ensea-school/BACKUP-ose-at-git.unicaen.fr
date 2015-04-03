<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatService;
use Common\Exception\RuntimeException;

trait FormuleResultatServiceAwareTrait
{
    /**
     * description
     *
     * @var FormuleResultatService
     */
    private $serviceFormuleResultatService;

    /**
     *
     * @param FormuleResultatService $serviceFormuleResultatService
     * @return self
     */
    public function setServiceFormuleResultatService( FormuleResultatService $serviceFormuleResultatService )
    {
        $this->serviceFormuleResultatService = $serviceFormuleResultatService;
        return $this;
    }

    /**
     *
     * @return FormuleResultatService
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleResultatService()
    {
        if (empty($this->serviceFormuleResultatService)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleResultatService');
        }else{
            return $this->serviceFormuleResultatService;
        }
    }

}