<?php

namespace Application\Entity\Db;

/**
 * VRolePersonnel
 */
class VRolePersonnel
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Role
     */
    protected $role;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var \Application\Entity\Db\TypeRole
     */
    protected $typeRole;

    /**
     * @var \Application\Entity\Db\Personnel
     */
    protected $personnel;

    /**
     * @var string
     */
    protected $phpRoleId;

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
     * Get role
     *
     * @return \Application\Entity\Db\Role 
     */
    public function getRole()
    {
        return $this->role;
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
     * Get typeRole
     *
     * @return \Application\Entity\Db\TypeRoleVRolePersonnel 
     */
    public function getTypeRole()
    {
        return $this->typeRole;
    }

    /**
     * Get personnel
     *
     * @return \Application\Entity\Db\Personnel 
     */
    public function getPersonnel()
    {
        return $this->personnel;
    }

    /**
     * Get phpRoleId
     *
     * @return string 
     */
    public function getPhpRoleId()
    {
        return $this->phpRoleId;
    }
}
