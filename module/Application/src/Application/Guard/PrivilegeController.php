<?php

namespace Application\Guard;

use BjyAuthorize\Guard\Controller;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Provider\Privilege\PrivilegeProviderAwareTrait;
use Application\Traits\SessionContainerTrait;


/**
 * Description of ControllerGuard
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeController extends Controller
{
    use PrivilegeProviderAwareTrait;
    use SessionContainerTrait;



    public function __construct(array $rules, ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        parent::__construct($this->privilegesToRoles($rules), $serviceLocator);
    }



    protected function privilegesToRoles(array $rules)
    {
//        $session = $this->getSessionContainer();
//        if (! isset($session->rules)){

        $pr = $this->getPrivilegeProvider()->getPrivilegesRoles();

        foreach ($rules as $index => $rule) {
            if (isset($rule['privileges'])) {
                $rolesCount    = 0;
                $privileges    = (array)$rule['privileges'];
                $rule['roles'] = isset($rule['roles']) ? (array)$rule['roles'] : [];
                foreach ($pr as $privilege => $roles) {
                    if (in_array($privilege, $privileges)) {
                        $rolesCount += count($roles);
                        $rule['roles'] = array_unique(array_merge($rule['roles'], $roles));
                    }
                }
                unset($rule['privileges']);
                if (0 == $rolesCount) {
                    unset($rules[$index]);
                } else {
                    $rules[$index] = $rule;
                }
            }
        }

        return $rules;
//            $session->rules = $rules;
//        }
//        return $session->rules;
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