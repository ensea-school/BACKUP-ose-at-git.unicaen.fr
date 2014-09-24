<?php

namespace Application\Entity\Db;

/**
 * RoleUtilisateur
 */
class RoleUtilisateur
{

    /**
     * @var integer
     */
    protected $isDefault;

    /**
     * @var string
     */
    protected $roleId;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\RoleUtilisateur
     */
    protected $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $utilisateur;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->utilisateur = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set isDefault
     *
     * @param integer $isDefault
     * @return RoleUtilisateur
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return integer 
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set roleId
     *
     * @param string $roleId
     * @return RoleUtilisateur
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return string 
     */
    public function getRoleId()
    {
        return $this->roleId;
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
     * Set parent
     *
     * @param \Application\Entity\Db\RoleUtilisateur $parent
     * @return RoleUtilisateur
     */
    public function setParent(\Application\Entity\Db\RoleUtilisateur $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Application\Entity\Db\RoleUtilisateur 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add utilisateur
     *
     * @param \Application\Entity\Db\Utilisateur $utilisateur
     * @return RoleUtilisateur
     */
    public function addUtilisateur(\Application\Entity\Db\Utilisateur $utilisateur)
    {
        $this->utilisateur[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \Application\Entity\Db\Utilisateur $utilisateur
     */
    public function removeUtilisateur(\Application\Entity\Db\Utilisateur $utilisateur)
    {
        $this->utilisateur->removeElement($utilisateur);
    }

    /**
     * Get utilisateur
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    public function __toString()
    {
        return $this->getRoleId();
    }
}