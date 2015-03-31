<?php

namespace Application\Entity\Db;

use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

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
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $role;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $privilege;

    /**
     *
     */
    public function __construct()
    {
        $this->role = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add privilege
     *
     * @param \Application\Entity\Db\Privilege $privilege
     * @return StatutIntervenant
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
     * @param ResourceInterface|string|null $resource
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrivilege( $resource=null )
    {
        return $this->privilege->filter( function(Privilege $privilege) use ($resource){
            if (empty($resource)){
                return true; // pas de filtre
            }
            if ($resource instanceof ResourceInterface){
                $resource = $resource->getResourceId();
            }
            return $privilege->getRessource()->getCode() === $resource;
        });
    }

    /**
     * Détermine si le type de rôle possède un provilège ou non.
     * Si le privilège transmis est un objet de classe Privilege, alors il est inutile de fournir la ressource, sinon il est obligatoire de la préciser
     *
     * @param Privilege|string $privilege
     * @param ResourceInterface|string|null $resource
     * @return boolean
     * @throws \Common\Exception\LogicException
     */
    public function hasPrivilege( $privilege, $resource=null )
    {
        if ($privilege instanceof Privilege){
            $resource  = $privilege->getRessource();
            $privilege = $privilege->getCode();
        }
        if (empty($resource)){
            throw new \Common\Exception\LogicException('La ressource du privilège n\'est pas précisée');
        }
        $privileges = $this->getPrivilege($resource);
        foreach( $privileges as $priv ){
            if ($priv->getCode() === $privilege) return true;
        }
        return false;
    }
}