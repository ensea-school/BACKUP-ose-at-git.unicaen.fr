<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * UtilisateurRole
 */
class UtilisateurRole implements \BjyAuthorize\Acl\HierarchicalRoleInterface
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
     * @var \Application\Entity\Db\UtilisateurRole
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getRoleId();
    }

    /**
     * Set isDefault
     *
     * @param integer $isDefault
     * @return UtilisateurRole
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
     * @return UtilisateurRole
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
     * @param \Application\Entity\Db\UtilisateurRole $parent
     * @return UtilisateurRole
     */
    public function setParent(\Application\Entity\Db\UtilisateurRole $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Application\Entity\Db\UtilisateurRole 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add user
     *
     * @param \Application\Entity\Db\Utilisateur $user
     * @return UtilisateurRole
     */
    public function addUser(\Application\Entity\Db\Utilisateur $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Application\Entity\Db\Utilisateur $user
     */
    public function removeUser(\Application\Entity\Db\Utilisateur $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->user;
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }
}
