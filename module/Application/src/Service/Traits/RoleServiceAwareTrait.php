<?php

namespace Application\Service\Traits;

use Application\Service\RoleService;

/**
 * Description of RoleAwareTrait
 *
 * @author UnicaenCode
 */
trait RoleServiceAwareTrait
{
    /**
     * @var RoleService
     */
    private $serviceRole;



    /**
     * @param RoleService $serviceRole
     *
     * @return self
     */
    public function setServiceRole(RoleService $serviceRole)
    {
        $this->serviceRole = $serviceRole;

        return $this;
    }



    /**
     * @return RoleService
     */
    public function getServiceRole()
    {
        if (empty($this->serviceRole)) {
            $this->serviceRole = \Application::$container->get(RoleService::class);
        }

        return $this->serviceRole;
    }
}