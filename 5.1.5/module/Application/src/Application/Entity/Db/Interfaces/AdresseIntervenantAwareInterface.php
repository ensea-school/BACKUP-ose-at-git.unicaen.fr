<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\AdresseIntervenant;

/**
 * Description of AdresseIntervenantAwareInterface
 *
 * @author UnicaenCode
 */
interface AdresseIntervenantAwareInterface
{
    /**
     * @param AdresseIntervenant $adresseIntervenant
     * @return self
     */
    public function setAdresseIntervenant( AdresseIntervenant $adresseIntervenant = null );



    /**
     * @return AdresseIntervenant
     */
    public function getAdresseIntervenant();
}