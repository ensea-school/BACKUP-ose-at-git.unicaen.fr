<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TauxHoraireHETD;

/**
 * Description of TauxHoraireHETDAwareTrait
 *
 * @author UnicaenCode
 */
trait TauxHoraireHETDAwareTrait
{
    protected ?TauxHoraireHETD $tauxHoraireHETD;



    /**
     * @param TauxHoraireHETD|null $tauxHoraireHETD
     *
     * @return self
     */
    public function setTauxHoraireHETD( ?TauxHoraireHETD $tauxHoraireHETD )
    {
        $this->tauxHoraireHETD = $tauxHoraireHETD;

        return $this;
    }



    public function getTauxHoraireHETD(): ?TauxHoraireHETD
    {
        return $this->tauxHoraireHETD;
    }
}