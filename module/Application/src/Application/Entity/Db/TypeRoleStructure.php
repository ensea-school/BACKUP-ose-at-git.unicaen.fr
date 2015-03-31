<?php

namespace Application\Entity\Db;

/**
 * TypeRoleStructure
 */
class TypeRoleStructure
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
     * @var \Application\Entity\Db\TypeStructure
     */
    protected $typeStructure;

    /**
     * @var \Application\Entity\Db\TypeRole
     */
    protected $typeRole;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;


    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return TypeRoleStructure
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
     * @return TypeRoleStructure
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
     * @return TypeRoleStructure
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
     * Set typeStructure
     *
     * @param \Application\Entity\Db\TypeStructure $typeStructure
     * @return TypeRoleStructure
     */
    public function setTypeStructure(\Application\Entity\Db\TypeStructure $typeStructure)
    {
        $this->typeStructure = $typeStructure;

        return $this;
    }

    /**
     * Get typeStructure
     *
     * @return \Application\Entity\Db\TypeStructure 
     */
    public function getTypeStructure()
    {
        return $this->typeStructure;
    }

    /**
     * Set typeRole
     *
     * @param \Application\Entity\Db\TypeRole $typeRole
     * @return TypeRoleStructure
     */
    public function setTypeRole(\Application\Entity\Db\TypeRole $typeRole)
    {
        $this->typeRole = $typeRole;

        return $this;
    }

    /**
     * Get typeRole
     *
     * @return \Application\Entity\Db\TypeRole 
     */
    public function getTypeRole()
    {
        return $this->typeRole;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return TypeRoleStructure
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
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return TypeRoleStructure
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
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return TypeRoleStructure
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
}
