<?php

namespace Application\Provider\Rule;

use BjyAuthorize\Provider\Rule\ProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Rule provider based on a given array of rules
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeRuleProvider implements ProviderInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait,
        \Application\Provider\Privilege\PrivilegeProviderAwareTrait,
        \Application\Traits\SessionContainerTrait
    ;


    /**
     * @param array $config
     */
    public function __construct( array $config, ServiceLocatorInterface $serviceLocator )
    {
        $this->setServiceLocator($serviceLocator);

        $session = $this->getSessionContainer();
//        if (! isset($session->rules)){
            $session->rules = $this->makeRules($config);
//        }
    }

    public function makeRules( array $config )
    {
        $pr = $this->getPrivilegeProvider()->getPrivilegesRoles();

        foreach( $config as $grant => $rules ){
            foreach( $rules as $index => $rule ){
                if (is_array($rule)){
                    $privileges = (array)$rule['privileges'];
                    $ressources = $rule['resources'];
                    $assertion = isset($rule['assertion']) ? $rule['assertion'] : null;
                    $bjyRoles = [];
                    foreach( $pr as $privilege => $roles ){
                        if (in_array($privilege, $privileges)){
                            $bjyRoles = array_unique( array_merge($bjyRoles, $roles) );
                        }
                    }

                    $bjyRule = [
                        $bjyRoles,
                        $ressources,
                        $privileges
                    ];
                    if ($assertion) $bjyRule[3] = $assertion;

                    $config[$grant][$index] = $bjyRule;
                }
            }
        }
        $rules = $config;
        if (! isset($rules['allow'])) $rules['allow'] = [];
        foreach( $pr as $privilege => $roles ){
            $rules['allow'][] = [
                $roles,
                'privilege/'.$privilege
            ];
        }
        return $rules;
    }

    /**
     * {@inheritDoc}
     */
    public function getRules()
    {
        return $this->getSessionContainer()->rules;
    }
}
