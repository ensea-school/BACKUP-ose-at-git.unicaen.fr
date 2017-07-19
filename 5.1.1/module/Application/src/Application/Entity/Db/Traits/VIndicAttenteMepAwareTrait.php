<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VIndicAttenteMep;

/**
 * Description of VIndicAttenteMepAwareTrait
 *
 * @author UnicaenCode
 */
trait VIndicAttenteMepAwareTrait
{
    /**
     * @var VIndicAttenteMep
     */
    private $vIndicAttenteMep;





    /**
     * @param VIndicAttenteMep $vIndicAttenteMep
     * @return self
     */
    public function setVIndicAttenteMep( VIndicAttenteMep $vIndicAttenteMep = null )
    {
        $this->vIndicAttenteMep = $vIndicAttenteMep;
        return $this;
    }



    /**
     * @return VIndicAttenteMep
     */
    public function getVIndicAttenteMep()
    {
        return $this->vIndicAttenteMep;
    }
}