<?php

namespace Application\Service\Traits;

use Application\Service\FormuleServiceModifie;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleServiceModifieAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceModifieAwareTrait
{
    /**
     * @var FormuleServiceModifie
     */
    private $serviceFormuleServiceModifie;





    /**
     * @param FormuleServiceModifie $serviceFormuleServiceModifie
     * @return self
     */
    public function setServiceFormuleServiceModifie( FormuleServiceModifie $serviceFormuleServiceModifie )
    {
        $this->serviceFormuleServiceModifie = $serviceFormuleServiceModifie;
        return $this;
    }



    /**
     * @return FormuleServiceModifie
     * @throws RuntimeException
     */
    public function getServiceFormuleServiceModifie()
    {
        if (empty($this->serviceFormuleServiceModifie)){
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
        $this->serviceFormuleServiceModifie = $serviceLocator->get('ApplicationFormuleServiceModifie');
        }
        return $this->serviceFormuleServiceModifie;
    }
}