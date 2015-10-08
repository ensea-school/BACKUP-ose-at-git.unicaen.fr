<?php

namespace Application\Service\Traits;

use Application\Service\FormuleService;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceAwareTrait
{
    /**
     * @var FormuleService
     */
    private $serviceFormule;





    /**
     * @param FormuleService $serviceFormule
     * @return self
     */
    public function setServiceFormule( FormuleService $serviceFormule )
    {
        $this->serviceFormule = $serviceFormule;
        return $this;
    }



    /**
     * @return FormuleService
     * @throws RuntimeException
     */
    public function getServiceFormule()
    {
        if (empty($this->serviceFormule)){
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
        $this->serviceFormule = $serviceLocator->get('ApplicationFormuleService');
        }
        return $this->serviceFormule;
    }
}