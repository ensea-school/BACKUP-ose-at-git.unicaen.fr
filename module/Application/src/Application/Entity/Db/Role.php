<?php

namespace Application\Entity\Db;

use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Role
 */
class Role implements HistoriqueAwareInterface, RoleInterface
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
     * Détermine si le rôle possède un provilège ou non.
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