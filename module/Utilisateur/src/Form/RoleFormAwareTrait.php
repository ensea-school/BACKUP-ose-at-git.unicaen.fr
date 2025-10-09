<?php

namespace Utilisateur\Form;


/**
 * Description of RoleFormAwareTrait
 *
 * @author UnicaenCode
 */
trait RoleFormAwareTrait
{
    protected ?RoleForm $formDroitsRole = null;



    /**
     * @param \Utilisateur\Form\RoleForm $formDroitsRole
     *
     * @return self
     */
    public function setFormDroitsRole(?RoleForm $formDroitsRole)
    {
        $this->formDroitsRole = $formDroitsRole;

        return $this;
    }



    public function getFormDroitsRole(): ?RoleForm
    {
        if (!empty($this->formDroitsRole)) {
            return $this->formDroitsRole;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(RoleForm::class);
    }
}