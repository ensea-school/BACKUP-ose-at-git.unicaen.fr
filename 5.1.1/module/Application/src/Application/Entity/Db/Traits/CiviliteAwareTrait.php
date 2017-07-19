<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Civilite;

/**
 * Description of CiviliteAwareTrait
 *
 * @author UnicaenCode
 */
trait CiviliteAwareTrait
{
    /**
     * @var Civilite
     */
    private $civilite;





    /**
     * @param Civilite $civilite
     * @return self
     */
    public function setCivilite( Civilite $civilite = null )
    {
        $this->civilite = $civilite;
        return $this;
    }



    /**
     * @return Civilite
     */
    public function getCivilite()
    {
        return $this->civilite;
    }
}