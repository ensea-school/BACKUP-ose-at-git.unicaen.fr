<?php

namespace Application\Traits;

use <entityPath>\<entityClass>;

/**
 * Description of <entityClass>AwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait <entityClass>AwareTrait
{
    /**
     * @var <entityClass>
     */
    protected $<entityParam>;

    /**
     * @param <entityClass> $<entityParam>
     * @return self
     */
    public function set<entityClass>(<entityClass> $<entityParam> = null)
    {
        $this-><entityParam> = $<entityParam>;

        return $this;
    }

    /**
     * @return <entityClass>
     */
    public function get<entityClass>()
    {
        return $this-><entityParam>;
    }
}