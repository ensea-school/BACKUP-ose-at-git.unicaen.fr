<?php

namespace Application\Service\Traits;

use Application\Service\Privilege;
use Common\Exception\RuntimeException;

trait PrivilegeAwareTrait
{
    /**
     * description
     *
     * @var Privilege
     */
    private $servicePrivilege;

    /**
     *
     * @param Privilege $servicePrivilege
     * @return self
     */
    public function setServicePrivilege( Privilege $servicePrivilege )
    {
        $this->servicePrivilege = $servicePrivilege;
        return $this;
    }

    /**
     *
     * @return Privilege
     * @throws \Common\Exception\RuntimeException
     */
    public function getServicePrivilege()
    {
        if (empty($this->servicePrivilege)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationPrivilege');
        }else{
            return $this->servicePrivilege;
        }
    }

}