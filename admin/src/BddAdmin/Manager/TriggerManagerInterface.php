<?php

namespace BddAdmin\Manager;

interface TriggerManagerInterface extends ManagerInterface
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
    public function enableAll(): TriggerManagerInterface;



    /**
     * @return TriggerManagerInterface
     */
    public function disableAll(): TriggerManagerInterface;



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