<?php

namespace Application\Entity\Db;

/**
 * VolumeHoraire
 */
class VolumeHoraire implements HistoriqueAwareInterface
{
    /**
     * @var float
     */
    protected $heures;

    /**
     * @var \DateTime
     */
    protected $histoCreation;

    /**
     * @var \DateTime
     */
    protected $histoDestruction;

    /**
     * @var \DateTime
     */
    protected $histoModification;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;

    /**
     * @var \Application\Entity\Db\Service
     */
    protected $service;

    /**
     * @var \Application\Entity\Db\MotifNonPaiement
     */
    protected $motifNonPaiement;

    /**
     * @var \Application\Entity\Db\Periode
     */
    protected $periode;

    /**
     * @var \Application\Entity\Db\TypeIntervention
     */
    protected $typeIntervention;
    
    /**
     * @var \Application\Entity\Db\TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;
    
    /**
     * @var \Application\Entity\Db\Contrat
     */
    protected $contrat;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $validation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $etatVolumeHoraire;

    /**
     * remove
     *
     * @var boolean
     */
    protected $remove=false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultatVolumeHoraire;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleResultatVolumeHoraire = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return implode(" - ", array(
                "Id " . $this->getId(),
                $this->getService()->getStructureEns(),
                "Service " . $this->getService()->getId(),
                "EP " . $this->getService()->getElementPedagogique()->getSourceCode() . " ({$this->getService()->getElementPedagogique()->getId()})",
                $this->getHeures() . "h",
                $this->getTypeIntervention(),
                count($this->getValidation()) . " validations",
                $this->getContrat() ? "Contrat " . $this->getContrat()->getId() : "Aucun contrat",
                $this->getHistoDestructeur() ? "Supprimé" : $this->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT)
        ));
    }

    /**
     * Détermine si le volume horaire a vocation à être supprimé ou non
     */
    public function setRemove($remove)
    {
        $this->remove = (boolean)$remove;
        return $this;
    }

    public function getRemove()
    {
        return $this->remove;
    }

    /**
     * Set heures
     *
     * @param float $heures
     * @return VolumeHoraire
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
     * @return VolumeHoraire
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
     * @return VolumeHoraire
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
     * @return VolumeHoraire
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
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return VolumeHoraire
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
     * @return VolumeHoraire
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
     * @return VolumeHoraire
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
     * Set service
     *
     * @param \Application\Entity\Db\Service $service
     * @return VolumeHoraire
     */
    public function setService(\Application\Entity\Db\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Application\Entity\Db\Service 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set motifNonPaiement
     *
     * @param \Application\Entity\Db\MotifNonPaiement $motifNonPaiement
     * @return VolumeHoraire
     */
    public function setMotifNonPaiement(\Application\Entity\Db\MotifNonPaiement $motifNonPaiement = null)
    {
        $this->motifNonPaiement = $motifNonPaiement;

        return $this;
    }

    /**
     * Get motifNonPaiement
     *
     * @return \Application\Entity\Db\MotifNonPaiement 
     */
    public function getMotifNonPaiement()
    {
        return $this->motifNonPaiement;
    }

    /**
     * Set periode
     *
     * @param \Application\Entity\Db\Periode $periode
     * @return VolumeHoraire
     */
    public function setPeriode(\Application\Entity\Db\Periode $periode = null)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return \Application\Entity\Db\Periode 
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set typeIntervention
     *
     * @param \Application\Entity\Db\TypeIntervention $typeIntervention
     * @return VolumeHoraire
     */
    public function setTypeIntervention(\Application\Entity\Db\TypeIntervention $typeIntervention = null)
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }

    /**
     * Get typeIntervention
     *
     * @return \Application\Entity\Db\TypeIntervention 
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }

    /**
     * Set typeVolumeHoraire
     *
     * @param \Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire
     * @return VolumeHoraire
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
     * Set contrat
     *
     * @param \Application\Entity\Db\Contrat $contrat
     * @return VolumeHoraire
     */
    public function setContrat(\Application\Entity\Db\Contrat $contrat = null)
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * Get contrat
     *
     * @return \Application\Entity\Db\Contrat 
     */
    public function getContrat()
    {
        return $this->contrat;
    }

    /**
     * Add validation
     *
     * @param \Application\Entity\Db\Validation $validation
     * @return self
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

    /**
     * Get etatVolumeHoraire
     *
     * @return EtatVolumeHoraire
     */
    public function getEtatVolumeHoraire()
    {
        return $this->etatVolumeHoraire->first();
    }

    /**
     * Get formuleResultatVolumeHoraire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleResultatVolumeHoraire()
    {
        return $this->formuleResultatVolumeHoraire;
    }

}