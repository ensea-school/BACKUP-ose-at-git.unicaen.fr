<?php

namespace Application\Interfaces;

use <entityPath>\<entityClass>;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface <entityClass>AwareInterface
{

    /**
     * @param <entityClass> $<entityParam>
     * @return self
     */
    public function set<entityClass>(<entityClass> $<entityParam> = null);

    /**
     * @return <entityClass>
     */
    public function get<entityClass>();
}