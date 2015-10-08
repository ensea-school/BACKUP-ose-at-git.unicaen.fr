<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\AdresseIntervenant;

/**
 * Description of AdresseIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait AdresseIntervenantAwareTrait
{
    /**
     * @var AdresseIntervenant
     */
    private $adresseIntervenant;





    /**
     * @param AdresseIntervenant $adresseIntervenant
     * @return self
     */
    public function setAdresseIntervenant( AdresseIntervenant $adresseIntervenant = null )
    {
        $this->adresseIntervenant = $adresseIntervenant;
        return $this;
    }



    /**
     * @return AdresseIntervenant
     */
    public function getAdresseIntervenant()
    {
        return $this->adresseIntervenant;
    }
}