<?php

namespace Application\Entity\Db;

/**
 * WfEtape
 */
class WfEtape
{
    const CODE_DEBUT                    = 'DEBUT';
    const CODE_DONNEES_PERSO_SAISIE     = 'DONNEES_PERSO_SAISIE';
    const CODE_DONNEES_PERSO_VALIDATION = 'DONNEES_PERSO_VALIDATION';
    const CODE_SERVICE_SAISIE           = 'SERVICE_SAISIE';
    const CODE_SERVICE_VALIDATION       = 'SERVICE_VALIDATION';
    const CODE_REFERENTIEL_SAISIE       = 'REFERENTIEL_SAISIE';
    const CODE_REFERENTIEL_VALIDATION   = 'REFERENTIEL_VALIDATION';
    const CODE_PJ_SAISIE                = 'PJ_SAISIE';
    const CODE_PJ_VALIDATION            = 'PJ_VALIDATION';
    const CODE_CONSEIL_RESTREINT        = TypeAgrement::CODE_CONSEIL_RESTREINT;  // NB: c'est texto le code du type d'agrément
    const CODE_CONSEIL_ACADEMIQUE       = TypeAgrement::CODE_CONSEIL_ACADEMIQUE; // NB: c'est texto le code du type d'agrément
    const CODE_CONTRAT                  = 'CONTRAT';
    const CODE_FIN                      = 'FIN';

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $pertinFunc;

    /**
     * @var string
     */
    private $franchFunc;

    /**
     * @var string
     */
    private $pertinRuleClass;

    /**
     * @var string
     */
    private $franchRuleClass;

    /**
     * @var string
     */
    private $stepClass;

    /**
     * @var boolean
     */
    private $structureDependant;

    /**
     * @var boolean
     */
    private $visible;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $etapePrecedente;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $etapeSuivante;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etapePrecedente = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etapeSuivante   = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLibelle();
    }
    
    /**
     * Set code
     *
     * @param string $code
     * @return WfEtape
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
     * Set libelle
     *
     * @param string $libelle
     * @return WfEtape
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
     * Set pertinFunc
     *
     * @param string $pertinFunc
     * @return WfEtape
     */
    public function setPertinFunc($pertinFunc)
    {
        $this->pertinFunc = $pertinFunc;

        return $this;
    }

    /**
     * Get pertinFunc
     *
     * @return string 
     */
    public function getPertinFunc()
    {
        return $this->pertinFunc;
    }

    /**
     * Set franchFunc
     *
     * @param string $franchFunc
     * @return WfEtape
     */
    public function setFranchFunc($franchFunc)
    {
        $this->franchFunc = $franchFunc;

        return $this;
    }

    /**
     * Get franchFunc
     *
     * @return string 
     */
    public function getFranchFunc()
    {
        return $this->franchFunc;
    }

    /**
     * Set pertinRuleClass
     *
     * @param string $pertinRuleClass
     * @return WfEtape
     */
    public function setPertinRuleClass($pertinRuleClass)
    {
        $this->pertinRuleClass = $pertinRuleClass;

        return $this;
    }

    /**
     * Get pertinRuleClass
     *
     * @return string 
     */
    public function getPertinRuleClass()
    {
        return $this->pertinRuleClass;
    }

    /**
     * Set franchRuleClass
     *
     * @param string $franchRuleClass
     * @return WfEtape
     */
    public function setFranchRuleClass($franchRuleClass)
    {
        $this->franchRuleClass = $franchRuleClass;

        return $this;
    }

    /**
     * Get franchRuleClass
     *
     * @return string 
     */
    public function getFranchRuleClass()
    {
        return $this->franchRuleClass;
    }

    /**
     * Set stepClass
     *
     * @param string $stepClass
     * @return WfEtape
     */
    public function setStepClass($stepClass)
    {
        $this->stepClass = $stepClass;

        return $this;
    }

    /**
     * Get stepClass
     *
     * @return string 
     */
    public function getStepClass()
    {
        return $this->stepClass;
    }

    /**
     * Set structureDependant
     *
     * @param boolean $structureDependant
     * @return WfEtape
     */
    public function setStructureDependant($structureDependant)
    {
        $this->structureDependant = $structureDependant;

        return $this;
    }

    /**
     * Get structureDependant
     *
     * @return boolean 
     */
    public function getStructureDependant()
    {
        return $this->structureDependant;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return WfEtape
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
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
     * Add etapePrecedente
     *
     * @param \Application\Entity\Db\WfEtape $etapePrecedente
     * @return WfEtape
     */
    public function addEtapePrecedente(\Application\Entity\Db\WfEtape $etapePrecedente)
    {
        $this->etapePrecedente[] = $etapePrecedente;

        return $this;
    }

    /**
     * Remove etapePrecedente
     *
     * @param \Application\Entity\Db\WfEtape $etapePrecedente
     */
    public function removeEtapePrecedente(\Application\Entity\Db\WfEtape $etapePrecedente)
    {
        $this->etapePrecedente->removeElement($etapePrecedente);
    }

    /**
     * Get etapePrecedente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEtapePrecedente()
    {
        return $this->etapePrecedente;
    }

    /**
     * Add etapeSuivante
     *
     * @param \Application\Entity\Db\WfEtape $etapeSuivante
     * @return WfEtape
     */
    public function addEtapeSuivante(\Application\Entity\Db\WfEtape $etapeSuivante)
    {
        $this->etapeSuivante[] = $etapeSuivante;

        return $this;
    }

    /**
     * Remove etapeSuivante
     *
     * @param \Application\Entity\Db\WfEtape $etapeSuivante
     */
    public function removeEtapeSuivante(\Application\Entity\Db\WfEtape $etapeSuivante)
    {
        $this->etapeSuivante->removeElement($etapeSuivante);
    }

    /**
     * Get etapeSuivante
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEtapeSuivante()
    {
        return $this->etapeSuivante;
    }
}
