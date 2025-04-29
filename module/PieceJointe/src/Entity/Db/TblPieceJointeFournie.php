<?php

namespace PieceJointe\Entity\Db;

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
     * @var \Workflow\Entity\Db\Validation
     */
    private $validation;

    /**
     * @var \PieceJointe\Entity\Db\PieceJointe
     */
    private $pieceJointe;

    /**
     * @var \PieceJointe\Entity\Db\TypePieceJointe
     */
    private $typePieceJointe;

    /**
     * @var \Intervenant\Entity\Db\Intervenant
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
     * @var integer
     */
    private $dureeVie;

    /**
     * @var varchar
     */
    private $codeIntervenant;

    /**
     * @var integer
     */
    private $dateValidite;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $dateArchive;



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
     * @return \Workflow\Entity\Db\Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }



    /**
     * Get pieceJointe
     *
     * @return \PieceJointe\Entity\Db\PieceJointe
     */
    public function getPieceJointe()
    {
        return $this->pieceJointe;
    }



    /**
     * Get typePieceJointe
     *
     * @return \PieceJointe\Entity\Db\TypePieceJointe
     */
    public function getTypePieceJointe()
    {
        return $this->typePieceJointe;
    }



    /**
     * Get intervenant
     *
     * @return \Intervenant\Entity\Db\Intervenant
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



    /**
     * Get dureeVie
     *
     * @return integer
     */
    public function getDureeVie()
    {
        return $this->dureeVie;
    }



    /**
     * Get
     *
     * @return string
     */
    public function getCodeIntervenant()
    {
        return $this->codeIntervenant;
    }



    /**
     * Get dateValidite
     *
     * @return integer
     */
    public function getDateValidite()
    {
        return $this->dateValidite;
    }



    /**
     * Get dateArchive
     *
     * @return \Application\Entity\Db\Annee
     */
    public function getDateArchive()
    {
        return $this->dateArchive;
    }

}

