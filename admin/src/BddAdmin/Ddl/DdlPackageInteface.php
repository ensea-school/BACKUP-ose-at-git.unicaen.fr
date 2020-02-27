<?php

namespace BddAdmin\Ddl;

interface DdlPackageInteface extends DdlInterface
{
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