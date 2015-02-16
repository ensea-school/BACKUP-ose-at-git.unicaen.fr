<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * CcActivite
 */
class CcActivite implements HistoriqueAwareInterface
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var boolean
     */
    private $fa;

    /**
     * @var boolean
     */
    private $fc;

    /**
     * @var boolean
     */
    private $fcMajorees;

    /**
     * @var boolean
     */
    private $fi;

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
     * @var string
     */
    private $libelle;

    /**
     * @var boolean
     */
    private $referentiel;

    /**
     * @var integer
     */
    private $id;

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
     * Set code
     *
     * @param string $code
     * @return CcActivite
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set fa
     *
     * @param boolean $fa
     * @return CcActivite
     */
    public function setFa($fa)
    {
        $this->fa = $fa;

        return $this;
    }

    /**
     * Get fa
     *
     * @return boolean 
     */
    public function getFa()
    {
        return $this->fa;
    }

    /**
     * Set fc
     *
     * @param boolean $fc
     * @return CcActivite
     */
    public function setFc($fc)
    {
        $this->fc = $fc;

        return $this;
    }

    /**
     * Get fc
     *
     * @return boolean 
     */
    public function getFc()
    {
        return $this->fc;
    }

    /**
     * Set fcMajorees
     *
     * @param boolean $fcMajorees
     * @return CcActivite
     */
    public function setFcMajorees($fcMajorees)
    {
        $this->fcMajorees = $fcMajorees;

        return $this;
    }

    /**
     * Get fcMajorees
     *
     * @return boolean
     */
    public function getFcMajorees()
    {
        return $this->fcMajorees;
    }

    /**
     * Set fi
     *
     * @param boolean $fi
     * @return CcActivite
     */
    public function setFi($fi)
    {
        $this->fi = $fi;

        return $this;
    }

    /**
     * Get fi
     *
     * @return boolean 
     */
    public function getFi()
    {
        return $this->fi;
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return CcActivite
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
     * @return CcActivite
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
     * @return CcActivite
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
     * Set libelle
     *
     * @param string $libelle
     * @return CcActivite
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set referentiel
     *
     * @param boolean $referentiel
     * @return CcActivite
     */
    public function setReferentiel($referentiel)
    {
        $this->referentiel = $referentiel;

        return $this;
    }

    /**
     * Get referentiel
     *
     * @return boolean 
     */
    public function getReferentiel()
    {
        return $this->referentiel;
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
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return CcActivite
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
     * @return CcActivite
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
     * @return CcActivite
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
     * détermine si un type d'heures peut être appliqué à ce type d'activité de centre de coût ou non
     *
     * @param \Application\Entity\Db\TypeHeures $typeHeures
     * @return boolean
     */
    public function typeHeuresMatches( TypeHeures $typeHeures )
    {
        $code = $typeHeures->getCode();
        switch( $code ){
            case TypeHeures::FI         : return $this->getFi();
            case TypeHeures::FA         : return $this->getFa();
            case TypeHeures::FC         : return $this->getFc();
            case TypeHeures::FC_MAJOREES: return $this->getFcMajorees();
            case TypeHeures::REFERENTIEL: return $this->getReferentiel();
        }
        return false;
    }
}
