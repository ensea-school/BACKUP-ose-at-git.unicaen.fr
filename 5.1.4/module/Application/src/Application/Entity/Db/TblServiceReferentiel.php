<?php

namespace Application\Entity\Db;

/**
 * TblServiceReferentiel
 */
class TblServiceReferentiel
{
    /**
     * @var float
     */
    private $nbvh = 0;

    /**
     * @var boolean
     */
    private $peutSaisirService = false;

    /**
     * @var float
     */
    private $valide = 0;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypeVolumeHoraire
     */
    private $typeVolumeHoraire;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;



    /**
     * Get nbvh
     *
     * @return float
     */
    public function getNbvh()
    {
        return $this->nbvh;
    }



    /**
     * Get peutSaisirService
     *
     * @return boolean
     */
    public function getPeutSaisirService()
    {
        return $this->peutSaisirService;
    }



    /**
     * Get valide
     *
     * @return float
     */
    public function getValide()
    {
        return $this->valide;
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
     * Get typeVolumeHoraire
     *
     * @return \Application\Entity\Db\TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
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
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
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

