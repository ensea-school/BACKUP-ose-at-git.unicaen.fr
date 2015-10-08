<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatService;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleResultatServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceAwareTrait
{
    /**
     * @var FormuleResultatService
     */
    private $serviceFormuleResultat;





    /**
     * @param FormuleResultatService $serviceFormuleResultat
     * @return self
     */
    public function setServiceFormuleResultat( FormuleResultatService $serviceFormuleResultat )
    {
        $this->serviceFormuleResultat = $serviceFormuleResultat;
        return $this;
    }



    /**
     * @return FormuleResultatService
     * @throws RuntimeException
     */
    public function getServiceFormuleResultat()
    {
        if (empty($this->serviceFormuleResultat)){
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
        $this->serviceFormuleResultat = $serviceLocator->get('ApplicationFormuleResultatService');
        }
        return $this->serviceFormuleResultat;
    }
}