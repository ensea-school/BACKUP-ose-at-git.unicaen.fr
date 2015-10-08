<?php

namespace Application\Form\Droits\Interfaces;

use Application\Form\Droits\PrivilegesForm;
use RuntimeException;

/**
 * Description of PrivilegesFormAwareInterface
 *
 * @author UnicaenCode
 */
interface PrivilegesFormAwareInterface
{
    /**
     * @param PrivilegesForm $formDroitsPrivileges
     * @return self
     */
    public function setFormDroitsPrivileges( PrivilegesForm $formDroitsPrivileges );



    /**
     * @return PrivilegesFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormDroitsPrivileges();
}