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
    protected ?<targetClassname> $<variable> = null;



    /**
     * @param <targetClassname> $<variable>
     *                      
     * @return self
     */
    public function set<method>(?<targetClassname> $<variable>)
    {
        $this-><variable> = $<variable>;

        return $this;
    }
<if useGetter notrim>


    public function get<method>(): ?<targetClassname>
    {
        if (!empty($this-><variable>)) {
            return $this-><variable>;
        }
        
         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(<targetClassname>::class);
    }
<endif useGetter>
}