<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 */
class Service implements HistoriqueAwareInterface
{
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
     * @var \DateTime
     */
    protected $validiteDebut;

    /**
     * @var \DateTime
     */
    protected $validiteFin;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $volumeHoraire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $validationService;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    protected $intervenant;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structureAff;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structureEns;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * @var \Application\Entity\Db\Etablissement
     */
    protected $etablissement;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;

    /**
     * @var \Application\Entity\Db\Annee
     */
    protected $annee;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->volumeHoraire = new \Doctrine\Common\Collections\ArrayCollection();
        $this->validationService = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return Service
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
     * @return Service
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
     * @return Service
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
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return Service
     */
    public function setValiditeDebut($validiteDebut)
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }

    /**
     * Get validiteDebut
     *
     * @return \DateTime 
     */
    public function getValiditeDebut()
    {
        return $this->validiteDebut;
    }

    /**
     * Set validiteFin
     *
     * @param \DateTime $validiteFin
     * @return Service
     */
    public function setValiditeFin($validiteFin)
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }

    /**
     * Get validiteFin
     *
     * @return \DateTime 
     */
    public function getValiditeFin()
    {
        return $this->validiteFin;
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
     * Add volumeHoraire
     *
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     * @return Service
     */
    public function addVolumeHoraire(\Application\Entity\Db\VolumeHoraire $volumeHoraire)
    {
        $this->volumeHoraire[] = $volumeHoraire;

        return $this;
    }

    /**
     * Remove volumeHoraire
     *
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     */
    public function removeVolumeHoraire(\Application\Entity\Db\VolumeHoraire $volumeHoraire)
    {
        $this->volumeHoraire->removeElement($volumeHoraire);
    }

    /**
     * Get volumeHoraire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
    }

    /**
     * Add validationService
     *
     * @param \Application\Entity\Db\ValidationService $validationService
     * @return Service
     */
    public function addValidationService(\Application\Entity\Db\ValidationService $validationService)
    {
        $this->validationService[] = $validationService;

        return $this;
    }

    /**
     * Remove validationService
     *
     * @param \Application\Entity\Db\ValidationService $validationService
     */
    public function removeValidationService(\Application\Entity\Db\ValidationService $validationService)
    {
        $this->validationService->removeElement($validationService);
    }

    /**
     * Get validationService
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValidationService()
    {
        return $this->validationService;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return Service
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;
        if ($intervenant && ! $this->getStructureAff()){
            $this->setStructureAff( $intervenant->getStructure() );
        }
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
     * Set structureAff
     *
     * @param \Application\Entity\Db\Structure $structureAff
     * @return Service
     */
    public function setStructureAff(\Application\Entity\Db\Structure $structureAff = null)
    {
        $this->structureAff = $structureAff;

        return $this;
    }

    /**
     * Get structureAff
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getStructureAff()
    {
        return $this->structureAff;
    }

    /**
     * Set structureEns
     *
     * @param \Application\Entity\Db\Structure $structureEns
     * @return Service
     */
    public function setStructureEns(\Application\Entity\Db\Structure $structureEns = null)
    {
        $this->structureEns = $structureEns;

        return $this;
    }

    /**
     * Get structureEns
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getStructureEns()
    {
        return $this->structureEns;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return Service
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
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return Service
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
     * Set elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     * @return Service
     */
    public function setElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
    {
        $this->elementPedagogique = $elementPedagogique;
        if ($elementPedagogique && ! $this->getStructureEns()){
            $this->setStructureEns( $elementPedagogique->getStructure() );
        }
        return $this;
    }

    /**
     * Get elementPedagogique
     *
     * @return \Application\Entity\Db\ElementPedagogique 
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }

    /**
     * Set etablissement
     *
     * @param \Application\Entity\Db\Etablissement $etablissement
     * @return Service
     */
    public function setEtablissement(\Application\Entity\Db\Etablissement $etablissement = null)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return \Application\Entity\Db\Etablissement 
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return Service
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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return Service
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
