<?php

namespace BddAdmin\Manager;

interface UniqueConstraintManagerInterface extends ManagerInterface
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
     * @return TriggerManagerInterface
     */
    public function enableAll(): UniqueConstraintManagerInterface;



    /**
     * @return TriggerManagerInterface
     */
    public function disableAll(): UniqueConstraintManagerInterface;

}
