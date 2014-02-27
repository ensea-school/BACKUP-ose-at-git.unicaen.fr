<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * RoleUtilisateur
 */
class RoleUtilisateur implements \BjyAuthorize\Acl\HierarchicalRoleInterface
{
    /**
     * @var integer
     */
    private $isDefault;

    /**
     * @var string
     */
    private $roleId;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\RoleUtilisateur
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $utilisateurs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->utilisateurs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add Utilisateur
     *
     * @param \Application\Entity\Db\Utilisateur $utilisateur
     * @return RoleUtilisateur
     */
    public function addUtilisateur(\Application\Entity\Db\Utilisateur $utilisateur)
    {
        $this->utilisateurs[] = $utilisateur;

        return $this;
    }

    /**
     * Remove Utilisateur
     *
     * @param \Application\Entity\Db\Utilisateur $utilisateur
     */
    public function removeUtilisateur(\Application\Entity\Db\Utilisateur $utilisateur)
    {
        $this->utilisateurs->removeElement($utilisateur);
    }

    /**
     * Get Utilisateurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }
}
