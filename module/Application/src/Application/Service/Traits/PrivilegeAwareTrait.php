<?php

namespace Application\Service\Traits;

use Application\Service\Privilege;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServicePrivilege( Privilege $servicePrivilege )
    {
        $this->servicePrivilege = $servicePrivilege;
        return $this;
    }



    /**
     * @return Privilege
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
        $this->servicePrivilege = $serviceLocator->get('ApplicationPrivilege');
        }
        return $this->servicePrivilege;
    }
}