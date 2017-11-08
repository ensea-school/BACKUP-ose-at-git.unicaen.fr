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
     *
     * @return self
     */
    public function set<method>( <targetClass> $<variable> )
    {
        $this-><variable> = $<variable>;

        return $this;
    }
<if useGetter notrim>


    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return <targetClass>
     * @throws RuntimeException
     */
    public function get<method>() : <targetClass>
    {
        if ($this-><variable>){
            return $this-><variable>;
        }else{
            return Module::$serviceLocator->get('FormElementManager')->get(<targetClass>::class);
        }
    }
<endif useGetter>
}