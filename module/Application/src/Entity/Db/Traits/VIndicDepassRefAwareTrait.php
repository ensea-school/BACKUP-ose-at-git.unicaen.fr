<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VIndicDepassRef;

/**
 * Description of VIndicDepassRefAwareTrait
 *
 * @author UnicaenCode
 */
trait VIndicDepassRefAwareTrait
{
    /**
     * @var VIndicDepassRef
     */
    private $vIndicDepassRef;





    /**
     * @param VIndicDepassRef $vIndicDepassRef
     * @return self
     */
    public function setVIndicDepassRef( VIndicDepassRef $vIndicDepassRef = null )
    {
        $this->vIndicDepassRef = $vIndicDepassRef;
        return $this;
    }



    /**
     * @return VIndicDepassRef
     */
    public function getVIndicDepassRef()
    {
        return $this->vIndicDepassRef;
    }
}