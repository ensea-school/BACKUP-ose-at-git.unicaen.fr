<?php

namespace Application\Entity\Db;

/**
 * IntervenantExterieur
 */
class IntervenantExterieur extends Intervenant
{
    /**
     * @var \DateTime
     */
    protected $validiteDebut;

    /**
     * @var \DateTime
     */
    protected $validiteFin;

    /**
     * @var \Application\Entity\Db\TypePoste
     */
    protected $typePoste;

    /**
     * @var \Application\Entity\Db\RegimeSecu
     */
    protected $regimeSecu;

    /**
     * @var \Application\Entity\Db\TypeIntervenantExterieur
     */
    protected $typeIntervenantExterieur;

    /**
     * @var \Application\Entity\Db\SituationFamiliale
     */
    protected $situationFamiliale;

    /**
     * @var \Application\Entity\Db\Dossier
     */
    protected $dossier;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $contrat;

    /**
     * 
     */
    public function __construct()
    {
        $this->contrat = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get estUneFemme
     *
     * @return bool 
     */
    public function estUneFemme()
    {
        $civilite = $this->getDossier() ? $this->getDossier()->getCivilite() : $this->getCivilite();
        
        return Civilite::SEXE_F === $civilite->getSexe();
    }
    
    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return IntervenantExterieur
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
     * @return IntervenantExterieur
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
     * Set typePoste
     *
     * @param \Application\Entity\Db\TypePoste $typePoste
     * @return IntervenantExterieur
     */
    public function setTypePoste(\Application\Entity\Db\TypePoste $typePoste = null)
    {
        $this->typePoste = $typePoste;

        return $this;
    }

    /**
     * Get typePoste
     *
     * @return \Application\Entity\Db\TypePoste 
     */
    public function getTypePoste()
    {
        return $this->typePoste;
    }

    /**
     * Set regimeSecu
     *
     * @param \Application\Entity\Db\RegimeSecu $regimeSecu
     * @return IntervenantExterieur
     */
    public function setRegimeSecu(\Application\Entity\Db\RegimeSecu $regimeSecu = null)
    {
        $this->regimeSecu = $regimeSecu;

        return $this;
    }

    /**
     * Get regimeSecu
     *
     * @return \Application\Entity\Db\RegimeSecu 
     */
    public function getRegimeSecu()
    {
        return $this->regimeSecu;
    }

    /**
     * Set typeIntervenantExterieur
     *
     * @param \Application\Entity\Db\TypeIntervenantExterieur $typeIntervenantExterieur
     * @return IntervenantExterieur
     */
    public function setTypeIntervenantExterieur(\Application\Entity\Db\TypeIntervenantExterieur $typeIntervenantExterieur = null)
    {
        $this->typeIntervenantExterieur = $typeIntervenantExterieur;

        return $this;
    }

    /**
     * Get typeIntervenantExterieur
     *
     * @return \Application\Entity\Db\TypeIntervenantExterieur 
     */
    public function getTypeIntervenantExterieur()
    {
        return $this->typeIntervenantExterieur;
    }

    /**
     * Set situationFamiliale
     *
     * @param \Application\Entity\Db\SituationFamiliale $situationFamiliale
     * @return IntervenantExterieur
     */
    public function setSituationFamiliale(\Application\Entity\Db\SituationFamiliale $situationFamiliale = null)
    {
        $this->situationFamiliale = $situationFamiliale;

        return $this;
    }

    /**
     * Get situationFamiliale
     *
     * @return \Application\Entity\Db\SituationFamiliale 
     */
    public function getSituationFamiliale()
    {
        return $this->situationFamiliale;
    }

    /**
     * Set dossier
     *
     * @param \Application\Entity\Db\Dossier $dossier
     * @return IntervenantExterieur
     */
    public function setDossier(\Application\Entity\Db\Dossier $dossier = null)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return \Application\Entity\Db\Dossier 
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * Add contrat
     *
     * @param \Application\Entity\Db\Contrat $contrat
     * @return Intervenant
     */
    public function addContrat(\Application\Entity\Db\Contrat $contrat)
    {
        $this->contrat[] = $contrat;

        return $this;
    }

    /**
     * Remove contrat
     *
     * @param \Application\Entity\Db\Contrat $contrat
     */
    public function removeContrat(\Application\Entity\Db\Contrat $contrat)
    {
        $this->contrat->removeElement($contrat);
    }

    /**
     * Get contrat
     *
     * @param \Application\Entity\Db\TypeContrat $typeContrat
     * @param \Application\Entity\Db\Structure $structure
     * @param \Application\Entity\Db\Annee $annee
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContrat(TypeContrat $typeContrat = null, Structure $structure = null, Annee $annee = null)
    {
        if (null === $this->contrat) {
            return null;
        }
        
        $filter   = function(Contrat $contrat) use ($typeContrat, $structure, $annee) {
            if ($typeContrat && $typeContrat !== $contrat->getTypeContrat()) {
                return false;
            }
            if ($structure && $structure !== $contrat->getStructure()) {
                return false;
            }
            if ($annee && $annee !== $contrat->getAnnee()) {
                return false;
            }
            return true; 
        };
        $contrats = $this->contrat->filter($filter);
        
        return $contrats;
    }

    /**
     * Get contrat initial
     *
     * @return Contrat|null
     */
    public function getContratInitial()
    {
        if (!count($this->getContrat())) {
            return null;
        }
        
        $type = TypeContrat::CODE_CONTRAT;
        
        $filter   = function($contrat) use ($type) { return $type === $contrat->getTypeContrat()->getCode(); };
        $contrats = $this->getContrat()->filter($filter);

        return count($contrats) ? $contrats->first() : null;
    }

    /**
     * Get avenants
     *
     * @return Contrat[]|null
     */
    public function getAvenants()
    {
        $type = TypeContrat::CODE_AVENANT;
        
        $filter   = function(Contrat $contrat) use ($type) { return $type === $contrat->getTypeContrat()->getCode(); };
        $contrats = $this->getContrat()->filter($filter);
        
        return $contrats;
    }
    
    /**
     * Retourne l'adresse mail personnelle éventuelle.
     * Si elle est null et que le paramètre le demande, retourne l'adresse par défaut.
     *
     * @param bool $fallbackOnDefault
     * @return string 
     */
    public function getEmailPerso($fallbackOnDefault = false)
    {
        $mail = null;
        
        if ($this->getDossier()) {
            $mail = $this->getDossier()->getEmailPerso();
        }
        
        if (!$mail && $fallbackOnDefault) {
            $mail = $this->getEmail();
        }
        
        return $mail;
    }
}