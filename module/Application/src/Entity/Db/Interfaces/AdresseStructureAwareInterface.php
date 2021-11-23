<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\AdresseStructure;

/**
 * Description of AdresseStructureAwareInterface
 *
 * @author UnicaenCode
 */
interface AdresseStructureAwareInterface
{
    /**
     * @param AdresseStructure $adresseStructure
     * @return self
     */
    public function setAdresseStructure( AdresseStructure $adresseStructure = null );



    /**
     * @return AdresseStructure
     */
    public function getAdresseStructure();
}