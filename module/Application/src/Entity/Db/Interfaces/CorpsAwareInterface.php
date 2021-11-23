<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Corps;

/**
 * Description of CorpsAwareInterface
 *
 * @author UnicaenCode
 */
interface CorpsAwareInterface
{
    /**
     * @param Corps $corps
     * @return self
     */
    public function setCorps( Corps $corps = null );



    /**
     * @return Corps
     */
    public function getCorps();
}