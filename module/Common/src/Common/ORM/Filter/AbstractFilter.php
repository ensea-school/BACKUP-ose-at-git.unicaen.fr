<?php

namespace Common\ORM\Filter;

use Application\Module;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Description of AbstractFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
abstract class AbstractFilter extends SQLFilter
{
    protected function getServiceLocator()
    {
        return Module::$serviceLocator;
    }
}