<?php

namespace Application\Service\Traits;

use Application\Service\Privilege;

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
    private $servicePrivilege;



    /**
     * @param Privilege $servicePrivilege
     *
     * @return self
     */
    public function setServicePrivilege(Privilege $servicePrivilege)
    {
        $this->servicePrivilege = $servicePrivilege;

        return $this;
    }



    /**
     * @return Privilege
     */
    public function getServicePrivilege()
    {
        if (empty($this->servicePrivilege)) {
            $this->servicePrivilege = \Application::$container->get('ApplicationPrivilege');
        }

        return $this->servicePrivilege;
    }
}