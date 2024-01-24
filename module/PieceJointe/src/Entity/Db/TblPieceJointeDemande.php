<?php

namespace PieceJointe\Entity\Db;

/**
 * TblPieceJointeDemande
 */
class TblPieceJointeDemande
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \PieceJointe\Entity\Db\TypePieceJointe
     */
    private $typePieceJointe;

    /**
     * @var \Intervenant\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var float
     */
    private $heuresPourSeuil;

    /**
     * @var float
     */
    private $heuresPourSeuilHetd;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;

    /**
     * @var varchar
     */
    private $codeIntervenant;

    /**
     * @var integer
     */
    private $obligatoire;



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
     * @return float
     */
    public function getHeuresPourSeuil()
    {
        return $this->heuresPourSeuil;
    }



    /**
     * @return float
     */
    public function getHeuresPourSeuilHetd()
    {
        return $this->heuresPourSeuilHetd;
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
     * Get codeIntervenant
     *
     * @return string
     */

    public function getCodeIntervenant()
    {
        return $this->codeIntervenant;
    }



    /**
     * Piece jointe obligatoire
     *
     * @return boolean true|false
     */
    public function isObligatoire()
    {
        if ($this->obligatoire) {
            return true;
        }

        return false;
    }
}
