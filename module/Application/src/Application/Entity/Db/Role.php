<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Role
 */
class Role implements HistoriqueAwareInterface, RoleInterface
{
    use HistoriqueAwareTrait;

    const CODE_RESPONSABLE_COMPOSANTE  = 'responsable-composante';
    const CODE_GESTIONNAIRE_COMPOSANTE = 'gestionnaire-composante';

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
        $this->privilege = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \Application\Entity\Db\Privilege $privilege
     * @return self
     */
    public function addPrivilege(\Application\Entity\Db\Privilege $privilege)
    {
        $this->privilege[] = $privilege;

        return $this;
    }

    /**
     * Remove privilege
     *
     * @param \Application\Entity\Db\Privilege $privilege
     */
    public function removePrivilege(\Application\Entity\Db\Privilege $privilege)
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
}