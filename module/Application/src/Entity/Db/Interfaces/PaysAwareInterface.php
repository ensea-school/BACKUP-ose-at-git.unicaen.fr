<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Pays;

/**
 * Description of PaysAwareInterface
 *
 * @author UnicaenCode
 */
interface PaysAwareInterface
{
    /**
     * @param Pays $pays
     * @return self
     */
    public function setPays( Pays $pays = null );



    /**
     * @return Pays
     */
    public function getPays();
}