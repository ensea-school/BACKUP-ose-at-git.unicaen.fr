<?php

namespace Application\Entity\Db;

/**
 * Contrat
 */
class Contrat implements HistoriqueAwareInterface
{
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
     * @var \Application\Entity\Db\TypeContrat
     */
    private $typeContrat;

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
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    private $intervenant;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $volumeHoraire;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Validation
     */
    private $validation;

    /**
     * @var integer
     */
    private $numeroAvenant;
    
    /**
     * @var \Application\Entity\Db\Contrat
     */
    protected $contrat;

    /**
     * @var \DateTime
     */
    private $dateRetourSigne;

    /**
     * Libellé de cet objet.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
    
    /**
     * Libellé de cet objet.
     * 
     * @param $avecArticle boolean Inclure l'article défini (utile pour inclure le libellé dans une phrase)
     * @param $deLe boolean Activer la formulation "du"/"de l'" ou non
     * @return string
     */
    public function toString($avecArticle = false, $deLe = false)
    {
        if ($this->estUnAvenant()) {
            if ($this->getValidation()) {
                $template = ($avecArticle ? ($deLe ? "de l'avenant n°%s" : "l'avenant n°%s") : "avenant n°%s");
            }
            else {
                $template = ($avecArticle ? ($deLe ? "du projet d'avenant" : "le projet d'avenant") : "projet d'avenant");
            }
        }
        else {
            if ($this->getValidation()) {
                $template = ($avecArticle ? ($deLe ? "du contrat n°%s" : "le contrat n°%s") : "contrat n°%s");
            }
            else {
                $template = ($avecArticle ? ($deLe ? "du projet de contrat" : "le projet de contrat") : "projet de contrat");
            }
        }
        
        return ucfirst(sprintf($template, $this->getReference()));
    }
    
    /**
     * Retourne la référence (numéro) du contrat ou de l'avenant.
     * 
     * @return string
     */
    public function getReference()
    {
        if ($this->estUnAvenant()) {
            return sprintf("%s.%s", $this->getContrat()->getReference(), $this->getNumeroAvenant());
        }
        else {
            return sprintf("%s", $this->getId());
        }
    }
    
    /**
     * Indique s'il s'agit d'un avenant.
     * 
     * @return boolean
     */
    public function estUnAvenant()
    {
        return $this->getTypeContrat()->getCode() === TypeContrat::CODE_AVENANT;
    }
    
    /**
     * Set numeroAvenant
     *
     * @param integer $numeroAvenant
     * @return Contrat
     */
    public function setNumeroAvenant($numeroAvenant)
    {
        $this->numeroAvenant = $numeroAvenant;

        return $this;
    }

    /**
     * Get numeroAvenant
     *
     * @return integer 
     */
    public function getNumeroAvenant()
    {
        return $this->numeroAvenant;
    }
    
    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return Contrat
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
     * @return Contrat
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
     * @return Contrat
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
     * Set typeContrat
     *
     * @param \Application\Entity\Db\TypeContrat $typeContrat
     * @return Contrat
     */
    public function setTypeContrat(\Application\Entity\Db\TypeContrat $typeContrat = null)
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }

    /**
     * Get typeContrat
     *
     * @return \Application\Entity\Db\TypeContrat 
     */
    public function getTypeContrat()
    {
        return $this->typeContrat;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return Contrat
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
     * @return Contrat
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
     * @return Contrat
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
     * Set intervenant
     *
     * @param \Application\Entity\Db\IntervenantExterieur $intervenant
     * @return self
     */
    public function setIntervenant(\Application\Entity\Db\IntervenantExterieur $intervenant = null)
    {
        $this->intervenant = $intervenant;
        return $this;
    }

    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\IntervenantExterieur 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return Intervenant
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
     * Set validation
     *
     * @param \Application\Entity\Db\Validation $validation
     * @return Contrat
     */
    public function setValidation(\Application\Entity\Db\Validation $validation = null)
    {
        $this->validation = $validation;

        return $this;
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
     * Set contrat
     *
     * @param \Application\Entity\Db\Contrat $contrat
     * @return Contrat
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
     * Set dateRetourSigne
     *
     * @param \DateTime $dateRetourSigne
     * @return Contrat
     */
    public function setDateRetourSigne($dateRetourSigne)
    {
        $this->dateRetourSigne = $dateRetourSigne;

        return $this;
    }

    /**
     * Get dateRetourSigne
     *
     * @return \DateTime 
     */
    public function getDateRetourSigne()
    {
        return $this->dateRetourSigne;
    }
}
