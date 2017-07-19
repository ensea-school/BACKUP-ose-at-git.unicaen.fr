<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatServiceService;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleResultatServiceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceServiceAwareTrait
{
    /**
     * @var FormuleResultatServiceService
     */
    private $serviceFormuleResultatService;





    /**
     * @param FormuleResultatServiceService $serviceFormuleResultatService
     * @return self
     */
    public function setServiceFormuleResultatService( FormuleResultatServiceService $serviceFormuleResultatService )
    {
        $this->serviceFormuleResultatService = $serviceFormuleResultatService;
        return $this;
    }



    /**
     * @return FormuleResultatServiceService
     * @throws RuntimeException
     */
    public function getServiceFormuleResultatService()
    {
        if (empty($this->serviceFormuleResultatService)){
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
        $this->serviceFormuleResultatService = $serviceLocator->get('ApplicationFormuleResultatService');
        }
        return $this->serviceFormuleResultatService;
    }
}