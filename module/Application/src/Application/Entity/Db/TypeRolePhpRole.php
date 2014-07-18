<?php

namespace Application\Entity\Db;

/**
 * TypeRolePhpRole
 */
class TypeRolePhpRole
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\TypeRole
     */
    protected $typeRole;

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
     * Set type
     *
     * @param \Application\Entity\Db\TypeRole $typeRole
     * @return self
     */
    public function setTypeRole(\Application\Entity\Db\TypeRole $typeRole = null)
    {
        $this->typeRole = $typeRole;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeRole 
     */
    public function getTypeRole()
    {
        return $this->typeRole;
    }

    /**
     * Set phpRoleId
     *
     * @param string $phpRoleId
     * @return TypeRole
     */
    public function setPhpRoleId($phpRoleId)
    {
        $this->phpRoleId = $phpRoleId;

        return $this;
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
