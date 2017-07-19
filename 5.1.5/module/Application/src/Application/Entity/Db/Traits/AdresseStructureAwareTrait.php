<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\AdresseStructure;

/**
 * Description of AdresseStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait AdresseStructureAwareTrait
{
    /**
     * @var AdresseStructure
     */
    private $adresseStructure;





    /**
     * @param AdresseStructure $adresseStructure
     * @return self
     */
    public function setAdresseStructure( AdresseStructure $adresseStructure = null )
    {
        $this->adresseStructure = $adresseStructure;
        return $this;
    }



    /**
     * @return AdresseStructure
     */
    public function getAdresseStructure()
    {
        return $this->adresseStructure;
    }
}