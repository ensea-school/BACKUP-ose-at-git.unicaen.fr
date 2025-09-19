<?php

namespace Application\Provider\Role;


/**
 * Description of RoleProviderAwareTrait
 *
 * @author UnicaenCode
 */
trait RoleProviderAwareTrait
{
    protected ?RoleProvider $providerRoleRole = null;



    /**
     * @param RoleProvider $providerRoleRole
     *
     * @return self
     */
    public function setProviderRoleRole(?RoleProvider $providerRoleRole)
    {
        $this->providerRoleRole = $providerRoleRole;

        return $this;
    }



    public function getProviderRoleRole(): ?RoleProvider
    {
        if (empty($this->providerRoleRole)) {
            $this->providerRoleRole = \Framework\Application\Application::getInstance()->container()->get(RoleProvider::class);
        }

        return $this->providerRoleRole;
    }
}