<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VIndicDepassHcHorsRemuFc;

/**
 * Description of VIndicDepassHcHorsRemuFcAwareInterface
 *
 * @author UnicaenCode
 */
interface VIndicDepassHcHorsRemuFcAwareInterface
{
    /**
     * @param VIndicDepassHcHorsRemuFc $vIndicDepassHcHorsRemuFc
     * @return self
     */
    public function setVIndicDepassHcHorsRemuFc( VIndicDepassHcHorsRemuFc $vIndicDepassHcHorsRemuFc = null );



    /**
     * @return VIndicDepassHcHorsRemuFc
     */
    public function getVIndicDepassHcHorsRemuFc();
}