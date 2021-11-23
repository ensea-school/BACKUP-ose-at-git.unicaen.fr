<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VIndicAttenteMep;

/**
 * Description of VIndicAttenteMepAwareInterface
 *
 * @author UnicaenCode
 */
interface VIndicAttenteMepAwareInterface
{
    /**
     * @param VIndicAttenteMep $vIndicAttenteMep
     * @return self
     */
    public function setVIndicAttenteMep( VIndicAttenteMep $vIndicAttenteMep = null );



    /**
     * @return VIndicAttenteMep
     */
    public function getVIndicAttenteMep();
}