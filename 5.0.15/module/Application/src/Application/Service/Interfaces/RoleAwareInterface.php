<?php

namespace Application\Service\Interfaces;

use Application\Service\Role;
use RuntimeException;

/**
 * Description of RoleAwareInterface
 *
 * @author UnicaenCode
 */
interface RoleAwareInterface
{
    /**
     * @param Role $serviceRole
     * @return self
     */
    public function setServiceRole( Role $serviceRole );



    /**
     * @return RoleAwareInterface
     * @throws RuntimeException
     */
    public function getServiceRole();
}