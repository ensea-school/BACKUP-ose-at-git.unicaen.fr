<?php

namespace Application\Entity\Db;

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
     * @var \Application\Entity\Db\TypePieceJointe
     */
    private $typePieceJointe;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var float
     */
    private $heuresPourSeuil;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;

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
     * @return float
     */
    public function getHeuresPourSeuil()
    {
        return $this->heuresPourSeuil;
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
