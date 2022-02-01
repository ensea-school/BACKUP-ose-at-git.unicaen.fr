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
    protected ?RoleForm $formDroitsRole = null;



    /**
     * @param RoleForm $formDroitsRole
     *
     * @return self
     */
    public function setFormDroitsRole( ?RoleForm $formDroitsRole )
    {
        $this->formDroitsRole = $formDroitsRole;

        return $this;
    }



    public function getFormDroitsRole(): ?RoleForm
    {
        if (empty($this->formDroitsRole)){
            $this->formDroitsRole = \Application::$container->get('FormElementManager')->get(RoleForm::class);
        }

        return $this->formDroitsRole;
    }
}