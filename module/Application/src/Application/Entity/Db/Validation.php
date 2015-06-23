<?php

namespace Application\Entity\Db;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Validation
 */
class Validation implements HistoriqueAwareInterface, ResourceInterface
{
    const RESOURCE_ID_VALIDATION_DONNEES_PERSO = 'VALIDATION_DOSSIER';
    const RESOURCE_ID_VALIDATION_ENSEIGNEMENT  = 'VALIDATION_ENSEIGNEMENT';
    const RESOURCE_ID_VALIDATION_REFERENTIEL   = 'VALIDATION_REFERENTIEL';
    const RESOURCE_ID_CLOTURE_REALISE          = 'CLOTURE_REALISE';
    
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
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\TypeValidation
     */
    private $typeValidation;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoCreateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $volumeHoraire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $volumeHoraireReferentiel;

    /**
     * Représentation littérale de cvet objet.
     * 
     * @return string
     */
    public function __toString()
    {
        return sprintf("Validation du %s par %s",
                $this->getHistoCreation()->format(\Common\Constants::DATETIME_FORMAT), 
                $this->getHistoCreateur());
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return Validation
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
     * @return Validation
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
     * @return Validation
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
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return Validation
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return Validation
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
     * Set typeValidation
     *
     * @param \Application\Entity\Db\TypeValidation $typeValidation
     * @return Validation
     */
    public function setTypeValidation(\Application\Entity\Db\TypeValidation $typeValidation = null)
    {
        $this->typeValidation = $typeValidation;

        return $this;
    }

    /**
     * Get typeValidation
     *
     * @return \Application\Entity\Db\TypeValidation 
     */
    public function getTypeValidation()
    {
        return $this->typeValidation;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return Validation
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
     * @return Validation
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
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return Validation
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
     * Add volumeHoraire
     *
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     * @return self
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
     * Add volumeHoraireReferentiel
     *
     * @param \Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel
     * @return self
     */
    public function addVolumeHoraireReferentiel(\Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel)
    {
        $this->volumeHoraireReferentiel[] = $volumeHoraireReferentiel;

        return $this;
    }

    /**
     * Remove volumeHoraireReferentiel
     *
     * @param \Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel
     */
    public function removeVolumeHoraireReferentiel(\Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel)
    {
        $this->volumeHoraireReferentiel->removeElement($volumeHoraireReferentiel);
    }

    /**
     * Get volumeHoraireReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVolumeHoraireReferentiel()
    {
        return $this->volumeHoraireReferentiel;
    }
    
    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     * @see ResourceInterface
     */
    public function getResourceId()
    {
        switch ($this->getTypeValidation()->getCode()) {
            case TypeValidation::CODE_DONNEES_PERSO:
                return self::RESOURCE_ID_VALIDATION_DONNEES_PERSO;
                
            case TypeValidation::CODE_ENSEIGNEMENT:
                return self::RESOURCE_ID_VALIDATION_ENSEIGNEMENT;
                
            case TypeValidation::CODE_REFERENTIEL:
                return self::RESOURCE_ID_VALIDATION_REFERENTIEL;
                
            case TypeValidation::CODE_CLOTURE_REALISE:
                return self::RESOURCE_ID_CLOTURE_REALISE;
                
            default:
                break;
        }
        
        return 'Validation';
    }
}
