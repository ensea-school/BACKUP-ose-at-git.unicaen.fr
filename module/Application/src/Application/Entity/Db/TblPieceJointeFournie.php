<?php

namespace Application\Entity\Db;

/**
 * TblPieceJointeFournie
 */
class TblPieceJointeFournie
{
    /**
     * @var boolean
     */
    private $toDelete = '0';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Validation
     */
    private $validation;

    /**
     * @var \Application\Entity\Db\PieceJointe
     */
    private $pieceJointe;

    /**
     * @var \Application\Entity\Db\TypePieceJointe
     */
    private $typePieceJointe;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Fichier
     */
    private $fichier;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;



    /**
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return TblPieceJointeFournie
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
     * Set validation
     *
     * @param \Application\Entity\Db\Validation $validation
     *
     * @return TblPieceJointeFournie
     */
    public function setValidation(\Application\Entity\Db\Validation $validation = null)
    {
        $this->validation = $validation;

        return $this;
    }



    /**
     * Get validation
     *
     * @return \Application\Entity\Db\Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }



    /**
     * Set pieceJointe
     *
     * @param \Application\Entity\Db\PieceJointe $pieceJointe
     *
     * @return TblPieceJointeFournie
     */
    public function setPieceJointe(\Application\Entity\Db\PieceJointe $pieceJointe = null)
    {
        $this->pieceJointe = $pieceJointe;

        return $this;
    }



    /**
     * Get pieceJointe
     *
     * @return \Application\Entity\Db\PieceJointe
     */
    public function getPieceJointe()
    {
        return $this->pieceJointe;
    }



    /**
     * Set typePieceJointe
     *
     * @param \Application\Entity\Db\TypePieceJointe $typePieceJointe
     *
     * @return TblPieceJointeFournie
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
     * @return TblPieceJointeFournie
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
     * Set fichier
     *
     * @param \Application\Entity\Db\Fichier $fichier
     *
     * @return TblPieceJointeFournie
     */
    public function setFichier(\Application\Entity\Db\Fichier $fichier = null)
    {
        $this->fichier = $fichier;

        return $this;
    }



    /**
     * Get fichier
     *
     * @return \Application\Entity\Db\Fichier
     */
    public function getFichier()
    {
        return $this->fichier;
    }



    /**
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     *
     * @return TblPieceJointeFournie
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

