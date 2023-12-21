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
        if (empty($this-><variable>)) {
            $this-><variable> = \OseAdmin::instance()->container()->get(<targetClassname>::class);
        }
        
        return $this-><variable>;
    }
<endif useGetter>
}