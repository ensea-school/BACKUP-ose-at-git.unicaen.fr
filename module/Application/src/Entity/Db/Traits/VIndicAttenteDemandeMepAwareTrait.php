<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VIndicAttenteDemandeMep;

/**
 * Description of VIndicAttenteDemandeMepAwareTrait
 *
 * @author UnicaenCode
 */
trait VIndicAttenteDemandeMepAwareTrait
{
    /**
     * @var VIndicAttenteDemandeMep
     */
    private $vIndicAttenteDemandeMep;





    /**
     * @param VIndicAttenteDemandeMep $vIndicAttenteDemandeMep
     * @return self
     */
    public function setVIndicAttenteDemandeMep( VIndicAttenteDemandeMep $vIndicAttenteDemandeMep = null )
    {
        $this->vIndicAttenteDemandeMep = $vIndicAttenteDemandeMep;
        return $this;
    }



    /**
     * @return VIndicAttenteDemandeMep
     */
    public function getVIndicAttenteDemandeMep()
    {
        return $this->vIndicAttenteDemandeMep;
    }
}