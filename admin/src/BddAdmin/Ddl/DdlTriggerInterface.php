<?php

namespace BddAdmin\Ddl;

interface DdlTriggerInterface extends DdlInterface
{
    /**
     * @param string|array $name
     */
    public function enable($name);



    /**
     * @param string|array $name
     */
    public function disable($name);



    /**
     * @param string|array $name
     *
     * @return mixed
     */
    public function compiler($name);



    /**
     * @return mixed
     */
    public function compilerTout();
}