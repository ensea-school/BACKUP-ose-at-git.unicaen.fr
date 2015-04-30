<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultat;
use Common\Exception\RuntimeException;

trait FormuleResultatAwareTrait
{
    /**
     * description
     *
     * @var FormuleResultat
     */
    private $serviceFormuleResultat;

    /**
     *
     * @param FormuleResultat $serviceFormuleResultat
     * @return self
     */
    public function setServiceFormuleResultat( FormuleResultat $serviceFormuleResultat )
    {
        $this->serviceFormuleResultat = $serviceFormuleResultat;
        return $this;
    }

    /**
     *
     * @return FormuleResultat
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleResultat()
    {
        if (empty($this->serviceFormuleResultat)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleResultat');
        }else{
            return $this->serviceFormuleResultat;
        }
    }

}