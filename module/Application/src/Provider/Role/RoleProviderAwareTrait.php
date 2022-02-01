<?php

namespace Application\Provider\Role;


/**
 * Description of RoleProviderAwareTrait
 *
 * @author UnicaenCode
 */
trait RoleProviderAwareTrait
{
    protected ?RoleProvider $providerRoleRole;



    /**
     * @param RoleProvider|null $providerRoleRole
     *
     * @return self
     */
    public function setProviderRoleRole( ?RoleProvider $providerRoleRole )
    {
        $this->providerRoleRole = $providerRoleRole;

        return $this;
    }



    public function getProviderRoleRole(): ?RoleProvider
    {
        if (!$this->providerRoleRole){
            $this->providerRoleRole = \Application::$container->get(RoleProvider::class);
        }

        return $this->providerRoleRole;
    }
}