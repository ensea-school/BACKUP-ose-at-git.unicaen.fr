<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Privilege;

/**
 * Description of PrivilegeAwareTrait
 *
 * @author UnicaenCode
 */
trait PrivilegeAwareTrait
{
    /**
     * @var Privilege
     */
    private $privilege;





    /**
     * @param Privilege $privilege
     * @return self
     */
    public function setPrivilege( Privilege $privilege = null )
    {
        $this->privilege = $privilege;
        return $this;
    }



    /**
     * @return Privilege
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }
}