<?php

namespace BddAdmin\Manager;

interface MaterializedViewManagerInteface extends ManagerInterface
{
    /**
     * @param string|array $name
     */
    public function refresh($name);
}
