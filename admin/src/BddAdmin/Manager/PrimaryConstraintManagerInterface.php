<?php

namespace BddAdmin\Manager;

interface PrimaryConstraintManagerInterface extends ManagerInterface
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
    public function enableAll(): PrimaryConstraintManagerInterface;



    /**
     * @return TriggerManagerInterface
     */
    public function disableAll(): PrimaryConstraintManagerInterface;

}