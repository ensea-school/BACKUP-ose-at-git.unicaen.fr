<?php

namespace <namespace>;

use <targetFullClass>;
use Application\Module;

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
    protected $<variable>;



    /**
     * @param <targetClass> $<variable>
     * @return self
     */
    public function set<method>( <targetClass> $<variable> )
    {
        $this-><variable> = $<variable>;

        return $this;
    }
<if useGetter notrim>


    /**
     * @return <targetClass>
     */
    public function get<method>() : <targetClass>
    {
        if (!$this-><variable>){
            $this-><variable> = Module::$serviceLocator->get(<targetClass>::class);
        }

        return $this-><variable>;
    }
<endif useGetter>
}