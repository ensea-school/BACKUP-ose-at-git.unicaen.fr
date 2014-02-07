<?php

namespace Import\Model\Hydrator\Strategy;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class BooleanStrategy implements \Zend\Stdlib\Hydrator\Strategy\StrategyInterface
{
    public function extract($value)
    {
        return $value;
    }

    public function hydrate($value)
    {
        $value = in_array( $value, array('O', 'Y', 'TRUE', 'o', 'y', 'true', '1') );
        return $value;
    }
}