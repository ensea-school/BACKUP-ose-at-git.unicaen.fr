<?php

namespace Application\Entity\Db;

/**
 * VolumeHoraireRef
 */
class VolumeHoraireRef implements HistoriqueAwareInterface
{
    /**
     * @var float
     */
    private $heures;

    /**
     * @var \DateTime
     */
    private $histoCreation;

    /**
     * @var \DateTime
     */
    private $histoDestruction;

    /**
     * @var \DateTime
     */
    private $histoModification;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypeVolumeHoraire
     */
    private $typeVolumeHoraire;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoCreateur;

    /**
     * @var \Application\Entity\Db\ServiceReferentiel
     */
    private $serviceReferentiel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $validation;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->validation = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set heures
     *
     * @param float $heures
     * @return VolumeHoraireRef
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
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return VolumeHoraireRef
     */
    public function setHistoCreation($histoCreation)
    {
        $this->histoCreation = $histoCreation;

        return $this;
    }

    /**
     * Get histoCreation
     *
     * @return \DateTime 
     */
    public function getHistoCreation()
    {
        return $this->histoCreation;
    }

    /**
     * Set histoDestruction
     *
     * @param \DateTime $histoDestruction
     * @return VolumeHoraireRef
     */
    public function setHistoDestruction($histoDestruction)
    {
        $this->histoDestruction = $histoDestruction;

        return $this;
    }

    /**
     * Get histoDestruction
     *
     * @return \DateTime 
     */
    public function getHistoDestruction()
    {
        return $this->histoDestruction;
    }

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return VolumeHoraireRef
     */
    public function setHistoModification($histoModification)
    {
        $this->histoModification = $histoModification;

        return $this;
    }

    /**
     * Get histoModification
     *
     * @return \DateTime 
     */
    public function getHistoModification()
    {
        return $this->histoModification;
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
     * @return VolumeHoraireRef
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
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return VolumeHoraireRef
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return VolumeHoraireRef
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return VolumeHoraireRef
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }

    /**
     * Set serviceReferentiel
     *
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     * @return VolumeHoraireRef
     */
    public function setServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $serviceReferentiel = null)
    {
        $this->serviceReferentiel = $serviceReferentiel;

        return $this;
    }

    /**
     * Get serviceReferentiel
     *
     * @return \Application\Entity\Db\ServiceReferentiel 
     */
    public function getServiceReferentiel()
    {
        return $this->serviceReferentiel;
    }

    /**
     * Add validation
     *
     * @param \Application\Entity\Db\Validation $validation
     * @return VolumeHoraireRef
     */
    public function addValidation(\Application\Entity\Db\Validation $validation)
    {
        $this->validation[] = $validation;

        return $this;
    }

    /**
     * Remove validation
     *
     * @param \Application\Entity\Db\Validation $validation
     */
    public function removeValidation(\Application\Entity\Db\Validation $validation)
    {
        $this->validation->removeElement($validation);
    }

    /**
     * Get validation
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValidation()
    {
        return $this->validation;
    }
}
