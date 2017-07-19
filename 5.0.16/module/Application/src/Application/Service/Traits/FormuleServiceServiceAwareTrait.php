<?php

namespace Application\Service\Traits;

use Application\Service\FormuleServiceService;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleServiceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceServiceAwareTrait
{
    /**
     * @var FormuleServiceService
     */
    private $serviceFormuleService;





    /**
     * @param FormuleServiceService $serviceFormuleService
     * @return self
     */
    public function setServiceFormuleService( FormuleServiceService $serviceFormuleService )
    {
        $this->serviceFormuleService = $serviceFormuleService;
        return $this;
    }



    /**
     * @return FormuleServiceService
     * @throws RuntimeException
     */
    public function getServiceFormuleService()
    {
        if (empty($this->serviceFormuleService)){
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
        $this->serviceFormuleService = $serviceLocator->get('ApplicationFormuleService');
        }
        return $this->serviceFormuleService;
    }
}