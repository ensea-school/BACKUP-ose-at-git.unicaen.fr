<?php

namespace Application\Entity\Db;

/**
 * Privilege
 */
class Privilege
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\CategoriePrivilege
     */
    private $categorie;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $role;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $statut;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->role = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statut = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Privilege
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Privilege
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
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
     * Set categoriePrivilege
     *
     * @param \Application\Entity\Db\CategoriePrivilege $categoriePrivilege
     * @return Privilege
     */
    public function setCategoriePrivilege(\Application\Entity\Db\CategoriePrivilege $categoriePrivilege = null)
    {
        $this->categoriePrivilege = $categoriePrivilege;

        return $this;
    }

    /**
     * Get categoriePrivilege
     *
     * @return \Application\Entity\Db\CategoriePrivilege
     */
    public function getCategoriePrivilege()
    {
        return $this->categoriePrivilege;
    }

    /**
     * Add role
     *
     * @param \Application\Entity\Db\Role $role
     * @return Privilege
     */
    public function addRole(\Application\Entity\Db\Role $role)
    {
        $this->Role[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \Application\Entity\Db\Role $role
     */
    public function removeRole(\Application\Entity\Db\Role $role)
    {
        $this->role->removeElement($role);
    }

    /**
     * Get role
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRole()
    {
        return $this->role;
    }

    public function getRoleCodes()
    {
        $result = [];
        foreach( $this->role as $role ){
            /* @var $role Role */
            $result[] = $role->getCode();
        }
        return $result;
    }

    /**
     * Add statut
     *
     * @param \Application\Entity\Db\StatutIntervenant $statut
     * @return Privilege
     */
    public function addStatut(\Application\Entity\Db\StatutIntervenant $statut)
    {
        $this->statut[] = $statut;

        return $this;
    }

    /**
     * Remove statut
     *
     * @param \Application\Entity\Db\StatutIntervenant $statut
     */
    public function removeStatut(\Application\Entity\Db\StatutIntervenant $statut)
    {
        $this->statut->removeElement($statut);
    }

    /**
     * Get statut
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStatut()
    {
        return $this->statut;
    }
}
