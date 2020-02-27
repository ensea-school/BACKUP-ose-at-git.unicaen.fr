<?php

namespace BddAdmin\Ddl;

interface DdlPrimaryConstraintInterface extends DdlInterface
{
    /**
     * @param string|array $name
     */
    public function enable($name);



    /**
     * @param string|array $name
     */
    public function disable($name);
}