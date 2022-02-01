<?php

namespace <namespace>;

<if subDir>use <targetClass>;
<endif subDir>

/**
 * Description of <classname>
 *
 * @author UnicaenCode
 */
trait <classname>
{
    protected ?<targetClassname> $<variable>;



    /**
     * @param <targetClassname>|null $<variable>
     *                      
     * @return self
     */
    public function set<method>( ?<targetClassname> $<variable> )
    {
        $this-><variable> = $<variable>;

        return $this;
    }
<if useGetter notrim>


    public function get<method>(): ?<targetClassname>
    {
        if (!$this-><variable>){
            $this-><variable> = \Application::$container->get(<targetClassname>::class);
        }
        
        return $this-><variable>;
    }
<endif useGetter>
}