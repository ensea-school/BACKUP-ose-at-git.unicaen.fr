<?php

namespace Application\Form\Droits\Traits;

use Application\Form\Droits\RoleForm;

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
     *
     * @return self
     */
    public function setFormDroitsRole(RoleForm $formDroitsRole)
    {
        $this->formDroitsRole = $formDroitsRole;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return RoleForm
     */
    public function getFormDroitsRole()
    {
        if (!empty($this->formDroitsRole)) {
            return $this->formDroitsRole;
        }

        return \Application::$container->get('FormElementManager')->get(RoleForm::class);
    }
}