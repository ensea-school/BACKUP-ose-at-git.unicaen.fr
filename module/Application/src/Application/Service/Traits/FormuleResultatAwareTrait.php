<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultat;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleResultatAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatAwareTrait
{
    /**
     * @var FormuleResultat
     */
    private $serviceFormuleResultat;





    /**
     * @param FormuleResultat $serviceFormuleResultat
     * @return self
     */
    public function setServiceFormuleResultat( FormuleResultat $serviceFormuleResultat )
    {
        $this->serviceFormuleResultat = $serviceFormuleResultat;
        return $this;
    }



    /**
     * @return FormuleResultat
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
        $this->serviceFormuleResultat = $serviceLocator->get('ApplicationFormuleResultat');
        }
        return $this->serviceFormuleResultat;
    }
}