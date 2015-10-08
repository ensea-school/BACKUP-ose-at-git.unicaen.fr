<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\AdresseIntervenantPrinc;

/**
 * Description of AdresseIntervenantPrincAwareTrait
 *
 * @author UnicaenCode
 */
trait AdresseIntervenantPrincAwareTrait
{
    /**
     * @var AdresseIntervenantPrinc
     */
    private $adresseIntervenantPrinc;





    /**
     * @param AdresseIntervenantPrinc $adresseIntervenantPrinc
     * @return self
     */
    public function setAdresseIntervenantPrinc( AdresseIntervenantPrinc $adresseIntervenantPrinc = null )
    {
        $this->adresseIntervenantPrinc = $adresseIntervenantPrinc;
        return $this;
    }



    /**
     * @return AdresseIntervenantPrinc
     */
    public function getAdresseIntervenantPrinc()
    {
        return $this->adresseIntervenantPrinc;
    }
}