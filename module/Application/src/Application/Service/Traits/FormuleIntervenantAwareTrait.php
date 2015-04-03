<?php

namespace Application\Service\Traits;

use Application\Service\FormuleIntervenant;
use Common\Exception\RuntimeException;

trait FormuleIntervenantAwareTrait
{
    /**
     * description
     *
     * @var FormuleIntervenant
     */
    private $serviceFormuleIntervenant;

    /**
     *
     * @param FormuleIntervenant $serviceFormuleIntervenant
     * @return self
     */
    public function setServiceFormuleIntervenant( FormuleIntervenant $serviceFormuleIntervenant )
    {
        $this->serviceFormuleIntervenant = $serviceFormuleIntervenant;
        return $this;
    }

    /**
     *
     * @return FormuleIntervenant
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleIntervenant()
    {
        if (empty($this->serviceFormuleIntervenant)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleIntervenant');
        }else{
            return $this->serviceFormuleIntervenant;
        }
    }

}