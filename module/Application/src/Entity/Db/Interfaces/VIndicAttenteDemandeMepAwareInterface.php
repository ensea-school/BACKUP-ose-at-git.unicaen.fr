<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VIndicAttenteDemandeMep;

/**
 * Description of VIndicAttenteDemandeMepAwareInterface
 *
 * @author UnicaenCode
 */
interface VIndicAttenteDemandeMepAwareInterface
{
    /**
     * @param VIndicAttenteDemandeMep $vIndicAttenteDemandeMep
     * @return self
     */
    public function setVIndicAttenteDemandeMep( VIndicAttenteDemandeMep $vIndicAttenteDemandeMep = null );



    /**
     * @return VIndicAttenteDemandeMep
     */
    public function getVIndicAttenteDemandeMep();
}