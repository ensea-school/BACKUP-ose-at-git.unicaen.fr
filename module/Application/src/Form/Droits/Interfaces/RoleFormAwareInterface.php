<?php

namespace Application\Form\Droits\Interfaces;

use Application\Form\Droits\RoleForm;
use RuntimeException;

/**
 * Description of RoleFormAwareInterface
 *
 * @author UnicaenCode
 */
interface RoleFormAwareInterface
{
    /**
     * @param RoleForm $formDroitsRole
     * @return self
     */
    public function setFormDroitsRole( RoleForm $formDroitsRole );



    /**
     * @return RoleFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormDroitsRole();
}