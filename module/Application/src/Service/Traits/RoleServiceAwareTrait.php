<?php

namespace Application\Service\Traits;

use Application\Service\RoleService;

/**
 * Description of RoleServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait RoleServiceAwareTrait
{
    protected ?RoleService $serviceRole;



    /**
     * @param RoleService|null $serviceRole
     *
     * @return self
     */
    public function setServiceRole( ?RoleService $serviceRole )
    {
        $this->serviceRole = $serviceRole;

        return $this;
    }



    public function getServiceRole(): ?RoleService
    {
        if (!$this->serviceRole){
            $this->serviceRole = \Application::$container->get(RoleService::class);
        }

        return $this->serviceRole;
    }
}