<?php

namespace Application\Guard;

use BjyAuthorize\Guard\Controller;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of ControllerGuard
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeController extends Controller
{
    use \Application\Service\Traits\PrivilegeAwareTrait
    ;

    public function __construct(array $rules, ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        parent::__construct( $this->privilegesToRoles($rules), $serviceLocator );
    }

    protected function privilegesToRoles( array $rules )
    {
        $pr = $this->getPrivilegesRoles();

        foreach( $rules as $index => $rule ){
            if (isset($rule['privileges'])){
                $privileges = (array)$rule['privileges'];
                $rule['roles'] = isset($rule['roles']) ? (array)$rule['roles'] : [];
                foreach( $pr as $privilege => $roles ){
                    if (in_array($privilege, $privileges)){
                        $rule['roles'] = array_unique( array_merge($rule['roles'], $roles) );
                    }
                }
                unset($rule['privileges']);
                $rules[$index] = $rule;
            }
        }
        return $rules;
    }

    protected function getPrivilegesRoles()
    {
        $privileges = $this->getServicePrivilege()->getList();
        /* @var $privileges \Application\Entity\Db\Privilege[] */

        $pr = [];
        foreach( $privileges as $privilege ){
            $roles = $privilege->getRoleCodes();
            if (! empty($roles)){
                $pr[$privilege->getCode()] = $roles;
            }
        }
        return $pr;
    }

    /**
     * Pour récupérer le serviceLocator depuis les traits de service
     *
     * @return ServiceLocatorInterface
     */
    protected function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}