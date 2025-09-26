<?php

namespace Utilisateur\Entity\Db;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenPrivilege\Entity\Db\PrivilegeCategorieInterface;
use UnicaenPrivilege\Entity\Db\PrivilegeInterface;

class CategoriePrivilege implements PrivilegeCategorieInterface
{
    protected int $id;

    protected string $code;

    protected string $libelle;

    protected int $ordre;

    protected Collection $privileges;



    public function __construct()
    {
        $this->privileges = new ArrayCollection();
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }



    public function getId(): int
    {
        return $this->id;
    }



    public function getCode(): string
    {
        return $this->code;
    }



    public function getLibelle(): string
    {
        return $this->libelle;
    }



    public function getOrdre()
    {
        return $this->ordre;
    }



    public function getPrivileges(): Collection
    {
        return $this->privileges;
    }



    public function setId($id)
    {
        // TODO: Implement setId() method.
    }



    public function setCode($code)
    {
        // TODO: Implement setCode() method.
    }



    public function setLibelle($libelle)
    {
        // TODO: Implement setLibelle() method.
    }



    public function getNamespace()
    {
        // TODO: Implement getNamespace() method.
        return null;
    }



    public function setNamespace($namespace)
    {
        // TODO: Implement setNamespace() method.
    }



    public function setOrdre($ordre)
    {
        // TODO: Implement setOrdre() method.
    }



    public function addPrivilege(PrivilegeInterface $privilege)
    {
        // TODO: Implement addPrivilege() method.
    }



    public function removePrivilege(PrivilegeInterface $privilege)
    {
        // TODO: Implement removePrivilege() method.
    }



    public function getClassname()
    {
        // TODO: Implement getClassname() method.
    }


}