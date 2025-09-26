<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenPrivilege\Entity\Db\PrivilegeInterface;

class CategoriePrivilege{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var int
     */
    protected $ordre;

    /**
     * @var ArrayCollection
     */
    protected $privileges;


    public function __construct()
    {
        $this->privileges = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getOrdre()
    {
        return $this->ordre;
    }

    public function setOrdre($ordre)
    {
        $this->ordre = (int)$ordre;

        return $this;
    }

    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * Add privilege.
     *
     * @param PrivilegeInterface $privilege
     */
    public function addPrivilege(PrivilegeInterface $privilege)
    {
        $this->privileges->add($privilege);

        return $this;
    }

    /**
     * Remove privilege.
     *
     * @param PrivilegeInterface $privilege
     */
    public function removePrivilege(PrivilegeInterface $privilege)
    {
        $this->privileges->removeElement($privilege);

        return $this;
    }

    /**
     * Get privileges class name
     *
     * @return string
     */
    public function getClassname()
    {
        return ucfirst(strtolower($this->getCode())) . "Privileges";
    }

}