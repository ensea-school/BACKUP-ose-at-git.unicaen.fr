<?php

namespace Application\Provider\Role;

trait RoleProviderAwareTrait
{

    /**
     * @var RoleProvider
     */
    private $providerRole;



    /**
     * @param RoleProvider $providerRole
     *
     * @return self
     */
    public function setProviderRole(RoleProvider $providerRole)
    {
        $this->providerRole = $providerRole;

        return $this;
    }



    /**
     * @return RoleProvider
     */
    public function getProviderRole()
    {
        if (!$this->providerRole) {
            $this->providerRole = \Application::$container->get(RoleProvider::class);
        }

        return $this->providerRole;
    }

}