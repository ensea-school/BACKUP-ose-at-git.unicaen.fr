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
    protected ?Perimetre $perimetre = null;



    /**
     * @param Perimetre $perimetre
     *
     * @return self
     */
    public function setPerimetre( Perimetre $perimetre )
    {
        $this->perimetre = $perimetre;

        return $this;
    }



    public function getPerimetre(): ?Perimetre
    {
        return $this->perimetre;
    }
}