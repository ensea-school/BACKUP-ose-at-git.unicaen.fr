<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Perimetre;

/**
 * Description of PerimetreAwareTrait
 *
 * @author UnicaenCode
 */
trait PerimetreAwareTrait
{
    /**
     * @var Perimetre
     */
    private $perimetre;





    /**
     * @param Perimetre $perimetre
     * @return self
     */
    public function setPerimetre( Perimetre $perimetre = null )
    {
        $this->perimetre = $perimetre;
        return $this;
    }



    /**
     * @return Perimetre
     */
    public function getPerimetre()
    {
        return $this->perimetre;
    }
}