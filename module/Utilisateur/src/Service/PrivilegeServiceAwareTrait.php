<?php

namespace Utilisateur\Service;

/**
 * Description of PrivilegeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PrivilegeServiceAwareTrait
{
    protected ?PrivilegeService $servicePrivilege = null;



    /**
     * @param PrivilegeService $servicePrivilege
     *
     * @return self
     */
    public function setServicePrivilege(?PrivilegeService $servicePrivilege)
    {
        $this->servicePrivilege = $servicePrivilege;

        return $this;
    }



    public function getServicePrivilege(): ?PrivilegeService
    {
        if (empty($this->servicePrivilege)) {
            $this->servicePrivilege = \Framework\Application\Application::getInstance()->container()->get(PrivilegeService::class);
        }

        return $this->servicePrivilege;
    }
}