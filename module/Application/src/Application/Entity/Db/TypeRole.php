<?php

namespace Application\Entity\Db;

use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * TypeRole
 */
class TypeRole implements HistoriqueAwareInterface, RoleInterface
{
    use HistoriqueAwareTrait;
 
    const CODE_RESPONSABLE_COMPOSANTE  = 'responsable-composante';
    const CODE_GESTIONNAIRE_COMPOSANTE = 'gestionnaire-composante';
    
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var \DateTime
     */
    protected $validiteDebut;

    /**
     * @var \DateTime
     */
    protected $validiteFin;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $role;

    /**
     *
     */
    public function __construct()
    {
        $this->role = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TypeRole
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
     * @return TypeRole
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
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return TypeRole
     */
    public function setValiditeDebut($validiteDebut)
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }

    /**
     * Get validiteDebut
     *
     * @return \DateTime 
     */
    public function getValiditeDebut()
    {
        return $this->validiteDebut;
    }

    /**
     * Set validiteFin
     *
     * @param \DateTime $validiteFin
     * @return TypeRole
     */
    public function setValiditeFin($validiteFin)
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }

    /**
     * Get validiteFin
     *
     * @return \DateTime 
     */
    public function getValiditeFin()
    {
        return $this->validiteFin;
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
     * Add role
     *
     * @param \Application\Entity\Db\Role $role
     * @return TypeRole
     */
    public function addRole(\Application\Entity\Db\Role $role)
    {
        $this->role[] = $role;

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
}