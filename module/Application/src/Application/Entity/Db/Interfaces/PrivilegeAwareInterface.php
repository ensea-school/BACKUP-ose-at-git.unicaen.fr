<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Privilege;

/**
 * Description of PrivilegeAwareInterface
 *
 * @author UnicaenCode
 */
interface PrivilegeAwareInterface
{
    /**
     * @param Privilege $privilege
     * @return self
     */
    public function setPrivilege( Privilege $privilege = null );



    /**
     * @return Privilege
     */
    public function getPrivilege();
}