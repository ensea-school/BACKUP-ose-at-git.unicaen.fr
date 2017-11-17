<?php

namespace <namespace>;

use <targetFullClass>;

/**
 * Description of <class>
 *
 * @author UnicaenCode
 */
trait <class>
{
    /**
     * @var <targetClass>
     */
    private $<variable>;





    /**
     * @param <targetClass> $<variable>
     * @return self
     */
    public function set<method>( <targetClass> $<variable> )
    {
        $this-><variable> = $<variable>;
        return $this;
    }



    /**
     * @return <targetClass>
     * @throws RuntimeException
     */
    public function get<method>()
    {
        if (empty($this-><variable>)){
            $this-><variable> = \Application::$container->get('HydratorManager')->get('<name>');
        }
        return $this-><variable>;
    }
}