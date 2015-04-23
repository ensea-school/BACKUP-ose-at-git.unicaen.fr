<?php

namespace Application\Provider\Rule;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use BjyAuthorize\Provider\Rule\ProviderInterface;


/**
 * Description of RuleProvider
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class RuleProvider implements ProviderInterface, ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    public function getRules()
    {
        return [];
    }

}
