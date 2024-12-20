<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\Collection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenPrivilege\Entity\Db\PrivilegeInterface;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\UserInterface;

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
    private $privileges;



    /**
     *
     */
    public function __construct()
    {
        $this->affectation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->privileges  = new \Doctrine\Common\Collections\ArrayCollection();
    }



    public function isDisplayed(): bool
    {
        return true;
    }



    public function setDisplayed(bool $accessibleExterieur): void
    {

    }



    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId(): string
    {
        return $this->getCode();
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString(): string
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
    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Get code
     *
     * @return string
     */
    public function getCode(): ?string
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
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }



    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle(): ?string
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
    public function setPerimetre($perimetre): RoleInterface
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
    public function isAccessibleExterieur(): bool
    {
        return $this->accessibleExterieur;
    }



    /**
     * @param bool $accessibleExterieur
     *
     * @return self
     */
    public function setAccessibleExterieur(bool $accessibleExterieur): void
    {
        $this->accessibleExterieur = $accessibleExterieur;
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
    public function getId(): ?int
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
    public function addPrivilege(PrivilegeInterface $privilege): void
    {
        $this->privileges[] = $privilege;
    }



    /**
     * Remove privilege
     *
     * @param Privilege $privilege
     */
    public function removePrivilege(PrivilegeInterface $privilege): void
    {
        $this->privileges->removeElement($privilege);
    }



    /**
     * Get privilege
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }



    /**
     * @param Privilege|string $privilege
     *
     * @return boolean
     */
    public function hasPrivilege($privilege)
    {
        if ($privilege instanceof Privilege) {
            return $this->getPrivileges()->contains($privilege);
        } else {
            $privileges = $this->getPrivileges();
            /* @var $privileges Privilege[] */
            foreach ($privileges as $priv) {
                if ($priv->getFullCode() === $privilege) return true;
            }

            return false;
        }
    }



    public function setId(?int $id): void
    {
        // TODO: Implement setId() method.
    }



    public function setRoleId(?string $roleId): void
    {
        // TODO: Implement setRoleId() method.
    }



    public function getDescription(): ?string
    {
        // TODO: Implement getDescription() method.
    }



    public function setDescription(?string $description): void
    {
        // TODO: Implement setDescription() method.
    }



    public function isDefault(): bool
    {
        // TODO: Implement isDefault() method.
    }



    public function setDefault(bool $default): void
    {
        // TODO: Implement setDefault() method.
    }



    public function isAuto(): bool
    {
        // TODO: Implement isAuto() method.
    }



    public function setAuto(bool $auto): void
    {
        // TODO: Implement setAuto() method.
    }



    public function getParent(): ?RoleInterface
    {
        // TODO: Implement getParent() method.
    }



    public function setParent(?RoleInterface $parent = null): void
    {
        // TODO: Implement setParent() method.
    }



    public function getLdapFilter(): ?string
    {
        // TODO: Implement getLdapFilter() method.
    }



    public function setLdapFilter(?string $ldapFilter): void
    {
        // TODO: Implement setLdapFilter() method.
    }



    public function getUsers(): Collection
    {
        // TODO: Implement getUsers() method.
    }



    public function addUser(UserInterface $user): void
    {
        // TODO: Implement addUser() method.
    }



    public function removeUser(UserInterface $user): void
    {
        // TODO: Implement removeUser() method.
    }

}