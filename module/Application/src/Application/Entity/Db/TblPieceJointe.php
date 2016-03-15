<?php

namespace Application\Entity\Db;

/**
 * TblPieceJointe
 */
class TblPieceJointe
{
    /**
     * @var boolean
     */
    private $demandee = '0';

    /**
     * @var boolean
     */
    private $fournie = '0';

    /**
     * @var boolean
     */
    private $toDelete = '0';

    /**
     * @var boolean
     */
    private $validee = '0';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypePieceJointe
     */
    private $typePieceJointe;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;


    /**
     * Set demandee
     *
     * @param boolean $demandee
     *
     * @return TblPieceJointe
     */
    public function setDemandee($demandee)
    {
        $this->demandee = $demandee;

        return $this;
    }

    /**
     * Get demandee
     *
     * @return boolean
     */
    public function getDemandee()
    {
        return $this->demandee;
    }

    /**
     * Set fournie
     *
     * @param boolean $fournie
     *
     * @return TblPieceJointe
     */
    public function setFournie($fournie)
    {
        $this->fournie = $fournie;

        return $this;
    }

    /**
     * Get fournie
     *
     * @return boolean
     */
    public function getFournie()
    {
        return $this->fournie;
    }

    /**
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return TblPieceJointe
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
     * Set validee
     *
     * @param boolean $validee
     *
     * @return TblPieceJointe
     */
    public function setValidee($validee)
    {
        $this->validee = $validee;

        return $this;
    }

    /**
     * Get validee
     *
     * @return boolean
     */
    public function getValidee()
    {
        return $this->validee;
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
     * Set typePieceJointe
     *
     * @param \Application\Entity\Db\TypePieceJointe $typePieceJointe
     *
     * @return TblPieceJointe
     */
    public function setTypePieceJointe(\Application\Entity\Db\TypePieceJointe $typePieceJointe = null)
    {
        $this->typePieceJointe = $typePieceJointe;

        return $this;
    }

    /**
     * Get typePieceJointe
     *
     * @return \Application\Entity\Db\TypePieceJointe
     */
    public function getTypePieceJointe()
    {
        return $this->typePieceJointe;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return TblPieceJointe
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     *
     * @return TblPieceJointe
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

