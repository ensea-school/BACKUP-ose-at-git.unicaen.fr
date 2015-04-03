<?php

namespace Application\Service\Traits;

use Application\Service\TypeRole;
use Common\Exception\RuntimeException;

trait TypeRoleAwareTrait
{
    /**
     * description
     *
     * @var TypeRole
     */
    private $serviceTypeRole;

    /**
     *
     * @param TypeRole $serviceTypeRole
     * @return self
     */
    public function setServiceTypeRole( TypeRole $serviceTypeRole )
    {
        $this->serviceTypeRole = $serviceTypeRole;
        return $this;
    }

    /**
     *
     * @return TypeRole
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeRole()
    {
        if (empty($this->serviceTypeRole)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeRole');
        }else{
            return $this->serviceTypeRole;
        }
    }

}