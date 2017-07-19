<?php

namespace Application\Entity\Db;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeModulateur
 */
class TypeModulateur implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    const FOAD = 'FOAD'; // Code du modulateur FOAD
    const FC   = 'FC'; // Code du modulateur FC
    const FIFC = 'FIFC'; // Code du modulateur mixte FI/FC

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var boolean
     */
    protected $obligatoire;

    /**
     * @var boolean
     */
    protected $publique;

    /**
     * @var boolean
     */
    protected $saisieParEnseignant;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $modulateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $elementPedagogique;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $etape;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $structure;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->modulateur = new \Doctrine\Common\Collections\ArrayCollection();
    }



    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeModulateur
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
     *
     * @return TypeModulateur
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
     * Set obligatoire
     *
     * @param boolean $obligatoire
     *
     * @return TypeModulateur
     */
    public function setObligatoire($obligatoire)
    {
        $this->obligatoire = $obligatoire;

        return $this;
    }



    /**
     * Get obligatoire
     *
     * @return boolean
     */
    public function getObligatoire()
    {
        return $this->obligatoire;
    }



    /**
     * Set publique
     *
     * @param boolean $publique
     *
     * @return TypeModulateur
     */
    public function setPublique($publique)
    {
        $this->publique = $publique;

        return $this;
    }



    /**
     * Get publique
     *
     * @return boolean
     */
    public function getPublique()
    {
        return $this->publique;
    }



    /**
     * Set saisieParEnseignant
     *
     * @param boolean $saisieParEnseignant
     *
     * @return TypeModulateur
     */
    public function setSaisieParEnseignant($saisieParEnseignant)
    {
        $this->saisieParEnseignant = $saisieParEnseignant;

        return $this;
    }



    /**
     * Get saisieParEnseignant
     *
     * @return boolean
     */
    public function getSaisieParEnseignant()
    {
        return $this->saisieParEnseignant;
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
     * Add modulateur
     *
     * @param \Application\Entity\Db\Modulateur $modulateur
     *
     * @return TypeModulateur
     */
    public function addModulateur(\Application\Entity\Db\Modulateur $modulateur)
    {
        $this->modulateur[] = $modulateur;

        return $this;
    }



    /**
     * Remove modulateur
     *
     * @param \Application\Entity\Db\Modulateur $modulateur
     */
    public function removeModulateur(\Application\Entity\Db\Modulateur $modulateur)
    {
        $this->modulateur->removeElement($modulateur);
    }



    /**
     * Get modulateur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModulateur()
    {
        return $this->modulateur;
    }



    /**
     * Get elementPedagogique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    /**
     * Get etape
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtape()
    {
        return $this->etape;
    }



    /**
     * Get structure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStructure()
    {
        return $this->structure;
    }
}
