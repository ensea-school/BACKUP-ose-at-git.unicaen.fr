<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VServiceNonValide;

/**
 * Description of VServiceNonValideAwareInterface
 *
 * @author UnicaenCode
 */
interface VServiceNonValideAwareInterface
{
    /**
     * @param VServiceNonValide $vServiceNonValide
     * @return self
     */
    public function setVServiceNonValide( VServiceNonValide $vServiceNonValide = null );



    /**
     * @return VServiceNonValide
     */
    public function getVServiceNonValide();
}