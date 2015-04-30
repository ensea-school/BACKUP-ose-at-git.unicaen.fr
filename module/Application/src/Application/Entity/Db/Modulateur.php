<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modulateur
 */
class Modulateur implements HistoriqueAwareInterface
{
    /**
     * @var string
     */
    protected $code;

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
     * @var string
     */
    protected $libelle;

    /**
     * @var float
     */
    protected $ponderationServiceCompl;

    /**
     * @var float
     */
    protected $ponderationServiceDu;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\TypeModulateur
     */
    protected $typeModulateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $elementModulateur;

    
    public function __toString()
    {
        return $this->getLibelle();
    }


    /**
     * Set code
     *
     * @param string $code
     * @return Modulateur
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
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return Modulateur
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
     * @return Modulateur
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
     * @return Modulateur
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
     * @return Modulateur
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
     * Set ponderationServiceCompl
     *
     * @param float $ponderationServiceCompl
     * @return Modulateur
     */
    public function setPonderationServiceCompl($ponderationServiceCompl)
    {
        $this->ponderationServiceCompl = $ponderationServiceCompl;

        return $this;
    }

    /**
     * Get ponderationServiceCompl
     *
     * @return float 
     */
    public function getPonderationServiceCompl()
    {
        return $this->ponderationServiceCompl;
    }

    /**
     * Set ponderationServiceDu
     *
     * @param float $ponderationServiceDu
     * @return Modulateur
     */
    public function setPonderationServiceDu($ponderationServiceDu)
    {
        $this->ponderationServiceDu = $ponderationServiceDu;

        return $this;
    }

    /**
     * Get ponderationServiceDu
     *
     * @return float 
     */
    public function getPonderationServiceDu()
    {
        return $this->ponderationServiceDu;
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
     * Set type
     *
     * @param \Application\Entity\Db\TypeModulateur $type
     * @return Modulateur
     */
    public function setTypeModulateur(\Application\Entity\Db\TypeModulateur $typeModulateur = null)
    {
        $this->typeModulateur = $typeModulateur;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeModulateur 
     */
    public function getTypeModulateur()
    {
        return $this->typeModulateur;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return Modulateur
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
     * @return Modulateur
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
     * @return Modulateur
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
     * Add elementModulateur
     *
     * @param \Application\Entity\Db\ElementModulateur $elementModulateur
     * @return self
     */
    public function addElementModulateur(\Application\Entity\Db\ElementModulateur $elementModulateur)
    {
        $this->elementModulateur[] = $elementModulateur;

        return $this;
    }

    /**
     * Remove elementModulateur
     *
     * @param \Application\Entity\Db\ElementModulateur $elementModulateur
     */
    public function removeElementModulateur(\Application\Entity\Db\Service $elementModulateur)
    {
        $this->elementModulateur->removeElement($elementModulateur);
    }

    /**
     * Get elementModulateur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementModulateur()
    {
        return $this->elementModulateur;
    }
}
