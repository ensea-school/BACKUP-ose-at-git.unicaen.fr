<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementPedagogique
 */
class ElementPedagogique
{
    /**
     * @var float
     */
    private $heures;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var boolean
     */
    private $nouveau;

    /**
     * @var string
     */
    private $sourceCode;

    /**
     * @var integer
     */
    private $tauxFoad;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypeFormation
     */
    private $typeFormation;

    /**
     * @var \Application\Entity\Db\SectionCnu
     */
    private $sectionCnu;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Source
     */
    private $source;

    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;


    /**
     * Set heures
     *
     * @param float $heures
     * @return ElementPedagogique
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
     * Set libelle
     *
     * @param string $libelle
     * @return ElementPedagogique
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
     * Set nouveau
     *
     * @param boolean $nouveau
     * @return ElementPedagogique
     */
    public function setNouveau($nouveau)
    {
        $this->nouveau = $nouveau;

        return $this;
    }

    /**
     * Get nouveau
     *
     * @return boolean 
     */
    public function getNouveau()
    {
        return $this->nouveau;
    }

    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return ElementPedagogique
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }

    /**
     * Get sourceCode
     *
     * @return string 
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }

    /**
     * Set tauxFoad
     *
     * @param integer $tauxFoad
     * @return ElementPedagogique
     */
    public function setTauxFoad($tauxFoad)
    {
        $this->tauxFoad = $tauxFoad;

        return $this;
    }

    /**
     * Get tauxFoad
     *
     * @return integer 
     */
    public function getTauxFoad()
    {
        return $this->tauxFoad;
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
     * Set typeFormation
     *
     * @param \Application\Entity\Db\TypeFormation $typeFormation
     * @return ElementPedagogique
     */
    public function setTypeFormation(\Application\Entity\Db\TypeFormation $typeFormation = null)
    {
        $this->typeFormation = $typeFormation;

        return $this;
    }

    /**
     * Get typeFormation
     *
     * @return \Application\Entity\Db\TypeFormation 
     */
    public function getTypeFormation()
    {
        return $this->typeFormation;
    }

    /**
     * Set sectionCnu
     *
     * @param \Application\Entity\Db\SectionCnu $sectionCnu
     * @return ElementPedagogique
     */
    public function setSectionCnu(\Application\Entity\Db\SectionCnu $sectionCnu = null)
    {
        $this->sectionCnu = $sectionCnu;

        return $this;
    }

    /**
     * Get sectionCnu
     *
     * @return \Application\Entity\Db\SectionCnu 
     */
    public function getSectionCnu()
    {
        return $this->sectionCnu;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return ElementPedagogique
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return ElementPedagogique
     */
    public function setSource(\Application\Entity\Db\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \Application\Entity\Db\Source 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return ElementPedagogique
     */
    public function setHistorique(\Application\Entity\Db\Historique $historique = null)
    {
        $this->historique = $historique;

        return $this;
    }

    /**
     * Get historique
     *
     * @return \Application\Entity\Db\Historique 
     */
    public function getHistorique()
    {
        return $this->historique;
    }
}
