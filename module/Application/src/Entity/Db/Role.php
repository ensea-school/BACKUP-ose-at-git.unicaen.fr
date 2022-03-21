<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenAuth\Entity\Db\Privilege;
use UnicaenAuth\Entity\Db\RoleInterface;
use UnicaenAuth\Entity\Db\UserInterface;

/**
 * Role
 */
class Role implements HistoriqueAwareInterface, RoleInterface
{
    use HistoriqueAwareTrait;

    const ADMINISTRATEUR = 'administrateur';

    /**
     * @var integer
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
     * @var Perimetre
     */
    protected $perimetre;

    /**
     * @var bool
     */
    protected $accessibleExterieur = true;

    /**
     * @var boolean
     */
    protected $peutChangerStructure;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $affectation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $privilege;



    /**
     *
     */
    public function __construct()
    {
        $this->affectation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->privilege   = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->getCode();
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * Set code
     *
     * @param string $code
     *
     * @return self
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
     *
     * @return self
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
     * Set perimetre
     *
     * @param Perimetre $perimetre
     *
     * @return self
     */
    public function setPerimetre($perimetre)
    {
        $this->perimetre = $perimetre;

        return $this;
    }



    /**
     * Get perimetre
     *
     * @return Perimetre
     */
    public function getPerimetre()
    {
        return $this->perimetre;
    }



    /**
     * @return bool
     */
    public function getAccessibleExterieur(): bool
    {
        return $this->accessibleExterieur;
    }



    /**
     * @param bool $accessibleExterieur
     *
     * @return self
     */
    public function setAccessibleExterieur(bool $accessibleExterieur): self
    {
        $this->accessibleExterieur = $accessibleExterieur;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getPeutChangerStructure()
    {
        return $this->peutChangerStructure;
    }



    /**
     * @param boolean $peutChangerStructure
     *
     * @return Role
     */
    public function setPeutChangerStructure($peutChangerStructure)
    {
        $this->peutChangerStructure = $peutChangerStructure;

        return $this;
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
     * Add affectation
     *
     * @param \Application\Entity\Db\Affectation $affectation
     *
     * @return self
     */
    public function addAffectation(\Application\Entity\Db\Affectation $affectation)
    {
        $this->affectation[] = $affectation;

        return $this;
    }



    /**
     * Remove affectation
     *
     * @param \Application\Entity\Db\Affectation $affectation
     */
    public function removeAffectation(\Application\Entity\Db\Affectation $affectation)
    {
        $this->affectation->removeElement($affectation);
    }



    /**
     * Get affectation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAffectation()
    {
        return $this->affectation;
    }



    /**
     * Add privilege
     *
     * @param Privilege $privilege
     *
     * @return self
     */
    public function addPrivilege(Privilege $privilege)
    {
        $this->privilege[] = $privilege;

        return $this;
    }



    /**
     * Remove privilege
     *
     * @param Privilege $privilege
     */
    public function removePrivilege(Privilege $privilege)
    {
        $this->privilege->removeElement($privilege);
    }



    /**
     * Get privilege
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }



    /**
     * @param Privilege|string $privilege
     *
     * @return boolean
     */
    public function hasPrivilege($privilege)
    {
        if ($privilege instanceof Privilege) {
            return $this->getPrivilege()->contains($privilege);
        } else {
            $privileges = $this->getPrivilege();
            /* @var $privileges Privilege[] */
            foreach ($privileges as $priv) {
                if ($priv->getFullCode() === $privilege) return true;
            }

            return false;
        }
    }



    public function setId($id)
    {
        // TODO: Implement setId() method.
    }



    public function setRoleId($roleId)
    {
        // TODO: Implement setRoleId() method.
    }



    public function getIsDefault()
    {
        // TODO: Implement getIsDefault() method.
    }



    public function setIsDefault($isDefault)
    {
        // TODO: Implement setIsDefault() method.
    }



    public function getParent()
    {
        // TODO: Implement getParent() method.
    }



    public function setParent(RoleInterface $parent = null)
    {
        // TODO: Implement setParent() method.
    }



    public function getLdapFilter()
    {
        // TODO: Implement getLdapFilter() method.
    }



    public function setLdapFilter($ldapFilter)
    {
        // TODO: Implement setLdapFilter() method.
    }



    public function getUsers()
    {
        // TODO: Implement getUsers() method.
    }



    public function addUser(UserInterface $user)
    {
        // TODO: Implement addUser() method.
    }

}