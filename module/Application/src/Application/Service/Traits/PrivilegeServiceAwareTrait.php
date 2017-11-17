<?php

namespace Application\Service\Traits;

use Application\Service\PrivilegeService;

/**
 * Description of PrivilegeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PrivilegeServiceAwareTrait
{
    /**
     * @var PrivilegeService
     */
    private $servicePrivilege;



    /**
     * @param PrivilegeService $servicePrivilege
     *
     * @return self
     */
    public function setServicePrivilege(PrivilegeService $servicePrivilege)
    {
        $this->servicePrivilege = $servicePrivilege;

        return $this;
    }



    /**
     * @return PrivilegeService
     */
    public function getServicePrivilege()
    {
        if (empty($this->servicePrivilege)) {
            $this->servicePrivilege = \Application::$container->get('UnicaenAuth\Service\Privilege');
        }

        return $this->servicePrivilege;
    }
}