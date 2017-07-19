<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Etablissement;

/**
 * Description of EtablissementAwareInterface
 *
 * @author UnicaenCode
 */
interface EtablissementAwareInterface
{
    /**
     * @param Etablissement $etablissement
     * @return self
     */
    public function setEtablissement( Etablissement $etablissement = null );



    /**
     * @return Etablissement
     */
    public function getEtablissement();
}