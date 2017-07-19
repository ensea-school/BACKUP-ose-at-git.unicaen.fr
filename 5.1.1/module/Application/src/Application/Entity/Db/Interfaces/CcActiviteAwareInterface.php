<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\CcActivite;

/**
 * Description of CcActiviteAwareInterface
 *
 * @author UnicaenCode
 */
interface CcActiviteAwareInterface
{
    /**
     * @param CcActivite $ccActivite
     * @return self
     */
    public function setCcActivite( CcActivite $ccActivite = null );



    /**
     * @return CcActivite
     */
    public function getCcActivite();
}