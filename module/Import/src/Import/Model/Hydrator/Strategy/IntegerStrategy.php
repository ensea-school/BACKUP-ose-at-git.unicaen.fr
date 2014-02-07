<?php

namespace Import\Model\Hydrator\Strategy;

/**
 *
 * 
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntegerStrategy implements \Zend\Stdlib\Hydrator\Strategy\StrategyInterface
{
    public function extract($value)
    {
        return $value;
    }

    public function hydrate($value)
    {
        if (null === $value || '' === $value) return null;
        return (integer)$value;
    }
}