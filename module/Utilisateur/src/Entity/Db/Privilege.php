<?php

namespace Utilisateur\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Framework\Authorize\Authorize;
use UnicaenPrivilege\Entity\Db\PrivilegeCategorieInterface;
use UnicaenPrivilege\Entity\Db\PrivilegeInterface;
use UnicaenUtilisateur\Entity\Db\RoleInterface;

class Privilege implements PrivilegeInterface
{
    protected int $id;

    protected string $code;

    protected string $libelle;

    protected int $ordre;

    protected CategoriePrivilege $categorie;

    protected Collection $roles;



    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }



    public function __toString()
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



    public function getFullCode(): string
    {
        return sprintf('%s-%s', $this->categorie->getCode(), $this->getCode());
    }



    public function getLibelle(): string
    {
        return $this->libelle;
    }



    function getOrdre(): int
    {
        return $this->ordre;
    }



    public function getCategorie(): CategoriePrivilege
    {
        return $this->categorie;
    }



    public function getResourceId(): string
    {
        return Authorize::privilegeResource($this);
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



    function setOrdre($ordre)
    {
        // TODO: Implement setOrdre() method.
    }



    public function setCategorie(PrivilegeCategorieInterface $categorie)
    {
        // TODO: Implement setCategorie() method.
    }



    public function getRoles()
    {
        return [];
    }



    public function addRole(RoleInterface $role)
    {
        // TODO: Implement addRole() method.
    }



    public function removeRole(RoleInterface $role)
    {
        // TODO: Implement removeRole() method.
    }



    function hasRole(RoleInterface $role)
    {
        return false;
    }


}

