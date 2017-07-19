<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Perimetre;

/**
 * Description of PerimetreAwareInterface
 *
 * @author UnicaenCode
 */
interface PerimetreAwareInterface
{
    /**
     * @param Perimetre $perimetre
     * @return self
     */
    public function setPerimetre( Perimetre $perimetre = null );



    /**
     * @return Perimetre
     */
    public function getPerimetre();
}