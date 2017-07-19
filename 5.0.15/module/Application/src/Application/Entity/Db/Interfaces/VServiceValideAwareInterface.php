<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VServiceValide;

/**
 * Description of VServiceValideAwareInterface
 *
 * @author UnicaenCode
 */
interface VServiceValideAwareInterface
{
    /**
     * @param VServiceValide $vServiceValide
     * @return self
     */
    public function setVServiceValide( VServiceValide $vServiceValide = null );



    /**
     * @return VServiceValide
     */
    public function getVServiceValide();
}