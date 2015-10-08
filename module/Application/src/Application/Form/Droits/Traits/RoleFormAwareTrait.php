<?php

namespace Application\Form\Droits\Traits;

use Application\Form\Droits\RoleForm;
use Application\Module;
use RuntimeException;

/**
 * Description of RoleFormAwareTrait
 *
 * @author UnicaenCode
 */
trait RoleFormAwareTrait
{
    /**
     * @var RoleForm
     */
    private $formDroitsRole;





    /**
     * @param RoleForm $formDroitsRole
     * @return self
     */
    public function setFormDroitsRole( RoleForm $formDroitsRole )
    {
        $this->formDroitsRole = $formDroitsRole;
        return $this;
    }



    /**
     * @return RoleForm
     * @throws RuntimeException
     */
    public function getFormDroitsRole()
    {
        if (empty($this->formDroitsRole)){
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
            $this->formDroitsRole = $serviceLocator->getServiceLocator('FormElementManager')->get('DroitsRoleForm');
        }
        return $this->formDroitsRole;
    }
}