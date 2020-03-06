<?php

namespace BddAdmin\Manager;

interface RefConstraintManagerInterface extends ManagerInterface
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