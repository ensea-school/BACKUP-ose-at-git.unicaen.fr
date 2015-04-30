<?php

namespace Application\Entity\Db;

/**
 * ElementModulateur
 */
class ElementModulateur implements HistoriqueAwareInterface
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
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Modulateur
     */
    protected $modulateur;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     */
    protected $element;

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
     * remove
     *
     * @var boolean
     */
    protected $remove=false;


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
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return ElementModulateur
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
     * @return ElementModulateur
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
     * @return ElementModulateur
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
     * Set modulateur
     *
     * @param \Application\Entity\Db\Modulateur $modulateur
     * @return ElementModulateur
     */
    public function setModulateur(\Application\Entity\Db\Modulateur $modulateur = null)
    {
        $this->modulateur = $modulateur;

        return $this;
    }

    /**
     * Get modulateur
     *
     * @return \Application\Entity\Db\Modulateur 
     */
    public function getModulateur()
    {
        return $this->modulateur;
    }

    /**
     * Set element
     *
     * @param \Application\Entity\Db\ElementPedagogique $element
     * @return ElementModulateur
     */
    public function setElement(\Application\Entity\Db\ElementPedagogique $element = null)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return \Application\Entity\Db\ElementPedagogique 
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return ElementModulateur
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
     * @return ElementModulateur
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
     * @return ElementModulateur
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
}
