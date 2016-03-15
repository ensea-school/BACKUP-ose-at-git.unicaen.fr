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
    private $nbvh = '0';

    /**
     * @var boolean
     */
    private $peutSaisirService = '0';

    /**
     * @var boolean
     */
    private $toDelete = '0';

    /**
     * @var float
     */
    private $valide = '0';

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
     * Set nbvh
     *
     * @param float $nbvh
     *
     * @return TblServiceReferentiel
     */
    public function setNbvh($nbvh)
    {
        $this->nbvh = $nbvh;

        return $this;
    }

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
     * Set peutSaisirService
     *
     * @param boolean $peutSaisirService
     *
     * @return TblServiceReferentiel
     */
    public function setPeutSaisirService($peutSaisirService)
    {
        $this->peutSaisirService = $peutSaisirService;

        return $this;
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
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return TblServiceReferentiel
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
     * Set valide
     *
     * @param float $valide
     *
     * @return TblServiceReferentiel
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
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
     * Set typeVolumeHoraire
     *
     * @param \Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return TblServiceReferentiel
     */
    public function setTypeVolumeHoraire(\Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire = null)
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return TblServiceReferentiel
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
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return TblServiceReferentiel
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
     * @return TblServiceReferentiel
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

