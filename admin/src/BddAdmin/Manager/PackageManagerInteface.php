<?php

namespace BddAdmin\Manager;

interface PackageManagerInteface extends ManagerInterface
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