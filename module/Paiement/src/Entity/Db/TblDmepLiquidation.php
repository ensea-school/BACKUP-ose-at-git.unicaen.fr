<?php

namespace Paiement\Entity\Db;

/**
 * TblDmepLiquidation
 */
class TblDmepLiquidation
{
    /**
     * @var float
     */
    private $heures = 0;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Paiement\Entity\Db\TypeRessource
     */
    private $typeRessource;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;



    /**
     * Get heures
     *
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Get typeRessource
     *
     * @return \Paiement\Entity\Db\TypeRessource
     */
    public function getTypeRessource()
    {
        return $this->typeRessource;
    }



    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * Get annee
     *
     * @return \Application\Entity\Db\Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}

