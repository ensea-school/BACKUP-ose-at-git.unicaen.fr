<?php

namespace Application\Service\Traits;

use Application\Service\PrivilegeService;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServicePrivilege( PrivilegeService $servicePrivilege )
    {
        $this->servicePrivilege = $servicePrivilege;
        return $this;
    }



    /**
     * @return PrivilegeService
     * @throws RuntimeException
     */
    public function getServicePrivilege()
    {
        if (empty($this->servicePrivilege)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->servicePrivilege = $serviceLocator->get('UnicaenAuth\Service\Privilege');
        }
        return $this->servicePrivilege;
    }
}