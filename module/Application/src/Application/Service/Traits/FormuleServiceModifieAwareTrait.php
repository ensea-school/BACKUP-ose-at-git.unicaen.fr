<?php

namespace Application\Service\Traits;

use Application\Service\FormuleServiceModifie;
use Common\Exception\RuntimeException;

trait FormuleServiceModifieAwareTrait
{
    /**
     * description
     *
     * @var FormuleServiceModifie
     */
    private $serviceFormuleServiceModifie;

    /**
     *
     * @param FormuleServiceModifie $serviceFormuleServiceModifie
     * @return self
     */
    public function setServiceFormuleServiceModifie( FormuleServiceModifie $serviceFormuleServiceModifie )
    {
        $this->serviceFormuleServiceModifie = $serviceFormuleServiceModifie;
        return $this;
    }

    /**
     *
     * @return FormuleServiceModifie
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleServiceModifie()
    {
        if (empty($this->serviceFormuleServiceModifie)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleServiceModifie');
        }else{
            return $this->serviceFormuleServiceModifie;
        }
    }

}