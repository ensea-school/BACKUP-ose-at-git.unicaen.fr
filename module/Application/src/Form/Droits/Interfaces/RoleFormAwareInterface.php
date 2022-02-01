<?php

namespace Application\Form\Droits\Interfaces;

use Application\Form\Droits\RoleForm;

/**
 * Description of RoleFormAwareInterface
 *
 * @author UnicaenCode
 */
interface RoleFormAwareInterface
{
    /**
     * @param RoleForm|null $formDroitsRole
     *
     * @return self
     */
    public function setFormDroitsRole( RoleForm $formDroitsRole );



    public function getFormDroitsRole(): ?RoleForm;
}