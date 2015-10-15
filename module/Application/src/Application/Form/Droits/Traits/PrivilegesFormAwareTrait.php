<?php

namespace Application\Form\Droits\Traits;

use Application\Form\Droits\PrivilegesForm;
use Application\Module;
use RuntimeException;

/**
 * Description of PrivilegesFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PrivilegesFormAwareTrait
{
    /**
     * @var PrivilegesForm
     */
    private $formDroitsPrivileges;





    /**
     * @param PrivilegesForm $formDroitsPrivileges
     * @return self
     */
    public function setFormDroitsPrivileges( PrivilegesForm $formDroitsPrivileges )
    {
        $this->formDroitsPrivileges = $formDroitsPrivileges;
        return $this;
    }



    /**
     * @return PrivilegesForm
     * @throws RuntimeException
     */
    public function getFormDroitsPrivileges()
    {
        if (empty($this->formDroitsPrivileges)){
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
            $this->formDroitsPrivileges = $serviceLocator->get('FormElementManager')->get('DroitsPrivilegesForm');
        }
        return $this->formDroitsPrivileges;
    }
}