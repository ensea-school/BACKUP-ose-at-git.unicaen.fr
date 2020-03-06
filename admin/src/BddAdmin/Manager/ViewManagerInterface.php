<?php

namespace BddAdmin\Manager;

interface ViewManagerInterface extends ManagerInterface
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
