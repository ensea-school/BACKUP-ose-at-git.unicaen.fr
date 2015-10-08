<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\AdresseIntervenantPrinc;

/**
 * Description of AdresseIntervenantPrincAwareInterface
 *
 * @author UnicaenCode
 */
interface AdresseIntervenantPrincAwareInterface
{
    /**
     * @param AdresseIntervenantPrinc $adresseIntervenantPrinc
     * @return self
     */
    public function setAdresseIntervenantPrinc( AdresseIntervenantPrinc $adresseIntervenantPrinc = null );



    /**
     * @return AdresseIntervenantPrinc
     */
    public function getAdresseIntervenantPrinc();
}