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
    /**
     * @var TauxHoraireHETD
     */
    private $tauxHoraireHETD;





    /**
     * @param TauxHoraireHETD $tauxHoraireHETD
     * @return self
     */
    public function setTauxHoraireHETD( TauxHoraireHETD $tauxHoraireHETD = null )
    {
        $this->tauxHoraireHETD = $tauxHoraireHETD;
        return $this;
    }



    /**
     * @return TauxHoraireHETD
     */
    public function getTauxHoraireHETD()
    {
        return $this->tauxHoraireHETD;
    }
}