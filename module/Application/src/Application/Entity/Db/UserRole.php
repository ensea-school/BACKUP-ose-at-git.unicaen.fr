<?php

namespace Application\Entity\Db;

use BjyAuthorize\Acl\HierarchicalRoleInterface;

/**
 * UserRole
 */
class UserRole implements HierarchicalRoleInterface
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
     * @var \Application\Entity\Db\UserRole
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return UserRole
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
     * @return UserRole
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
     * @param \Application\Entity\Db\UserRole $parent
     * @return UserRole
     */
    public function setParent(\Application\Entity\Db\UserRole $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Application\Entity\Db\UserRole 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add user
     *
     * @param \Application\Entity\Db\User $user
     * @return UserRole
     */
    public function addUser(\Application\Entity\Db\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Application\Entity\Db\User $user
     */
    public function removeUser(\Application\Entity\Db\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $user;


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
