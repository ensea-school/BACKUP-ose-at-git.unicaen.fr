<?php

namespace Application\Entity\Db;

/**
 * TblPieceJointeFournie
 */
class TblPieceJointeFournie
{
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Get pieceJointe
     *
     * @return \Application\Entity\Db\PieceJointe
     */
    public function getPieceJointe()
    {
        return $this->pieceJointe;
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
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
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
     * Get annee
     *
     * @return \Application\Entity\Db\Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}

