<?php

namespace Application\Entity\Db;

/**
 * TblDmepLiquidation
 */
class TblDmepLiquidation
{
    /**
     * @var float
     */
    private $heures = '0';

    /**
     * @var boolean
     */
    private $toDelete = '0';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypeRessource
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
     * Set heures
     *
     * @param float $heures
     *
     * @return TblDmepLiquidation
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }

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
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return TblDmepLiquidation
     */
    public function setToDelete($toDelete)
    {
        $this->toDelete = $toDelete;

        return $this;
    }

    /**
     * Get toDelete
     *
     * @return boolean
     */
    public function getToDelete()
    {
        return $this->toDelete;
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
     * Set typeRessource
     *
     * @param \Application\Entity\Db\TypeRessource $typeRessource
     *
     * @return TblDmepLiquidation
     */
    public function setTypeRessource(\Application\Entity\Db\TypeRessource $typeRessource = null)
    {
        $this->typeRessource = $typeRessource;

        return $this;
    }

    /**
     * Get typeRessource
     *
     * @return \Application\Entity\Db\TypeRessource
     */
    public function getTypeRessource()
    {
        return $this->typeRessource;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return TblDmepLiquidation
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     *
     * @return TblDmepLiquidation
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee = null)
    {
        $this->annee = $annee;

        return $this;
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

