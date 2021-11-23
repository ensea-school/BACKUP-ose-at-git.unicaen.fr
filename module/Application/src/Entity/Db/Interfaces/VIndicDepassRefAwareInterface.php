<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VIndicDepassRef;

/**
 * Description of VIndicDepassRefAwareInterface
 *
 * @author UnicaenCode
 */
interface VIndicDepassRefAwareInterface
{
    /**
     * @param VIndicDepassRef $vIndicDepassRef
     * @return self
     */
    public function setVIndicDepassRef( VIndicDepassRef $vIndicDepassRef = null );



    /**
     * @return VIndicDepassRef
     */
    public function getVIndicDepassRef();
}