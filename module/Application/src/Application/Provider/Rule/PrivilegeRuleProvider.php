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
        \Application\Provider\Privilege\PrivilegeProviderAwareTrait
    ;

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @param array $config
     */
    public function __construct( array $config, ServiceLocatorInterface $serviceLocator )
    {
        $this->setServiceLocator($serviceLocator);

        $pr = $this->getPrivilegeProvider()->getPrivilegesRoles();

        foreach( $config as $grant => $rules ){
            foreach( $rules as $index => $rule ){
                if (is_array($rule)){
                    $privileges = (array)$rule[0];
                    $rs = [];
                    foreach( $pr as $privilege => $roles ){
                        if (in_array($privilege, $privileges)){
                            $rs = array_unique( array_merge($rs, $roles) );
                        }
                    }
                    $config[$grant][$index][0] = $rs;
                }
            }
        }
        $this->rules = $config;
        if (! isset($this->rules['allow'])) $this->rules['allow'] = [];
        foreach( $pr as $privilege => $roles ){
            $this->rules['allow'][] = [
                $roles,
                'privilege/'.$privilege
            ];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getRules()
    {
        return $this->rules;
    }
}
