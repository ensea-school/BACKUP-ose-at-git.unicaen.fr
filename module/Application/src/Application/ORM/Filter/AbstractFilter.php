<?php

namespace Application\ORM\Filter;

use Doctrine\ORM\Query\Filter\SQLFilter;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Description of AbstractFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
abstract class AbstractFilter extends SQLFilter
{
    use ServiceLocatorAwareTrait;

    public function getServiceLocator()
    {
        if ($this->serviceLocator) return $this->serviceLocator;
        return \Application::$container;
    }
}