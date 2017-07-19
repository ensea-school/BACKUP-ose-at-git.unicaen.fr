<?php

namespace Application\Service\Traits;

use Application\Service\FormuleIntervenant;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleIntervenantAwareTrait
{
    /**
     * @var FormuleIntervenant
     */
    private $serviceFormuleIntervenant;





    /**
     * @param FormuleIntervenant $serviceFormuleIntervenant
     * @return self
     */
    public function setServiceFormuleIntervenant( FormuleIntervenant $serviceFormuleIntervenant )
    {
        $this->serviceFormuleIntervenant = $serviceFormuleIntervenant;
        return $this;
    }



    /**
     * @return FormuleIntervenant
     * @throws RuntimeException
     */
    public function getServiceFormuleIntervenant()
    {
        if (empty($this->serviceFormuleIntervenant)){
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
        $this->serviceFormuleIntervenant = $serviceLocator->get('ApplicationFormuleIntervenant');
        }
        return $this->serviceFormuleIntervenant;
    }
}