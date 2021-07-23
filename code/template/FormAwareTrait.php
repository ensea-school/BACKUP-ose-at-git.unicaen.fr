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
    /**
     * @var <targetClassname>
     */
    protected $<variable>;



    /**
     * @param <targetClassname> $<variable>
     *
     * @return self
     */
    public function set<method>( <targetClassname> $<variable> )
    {
        $this-><variable> = $<variable>;

        return $this;
    }
<if useGetter notrim>


    /**
     * @return <targetClassname>
     */
    public function get<method>(): ?<targetClassname>
    {
        if (!$this-><variable>){
            $this-><variable> = \Application::$container->get('FormElementManager')->get(<targetClassname>::class);
        }

        return $this-><variable>;
    }
<endif useGetter>
}