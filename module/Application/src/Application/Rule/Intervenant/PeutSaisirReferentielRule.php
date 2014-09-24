<?php

namespace Application\Rule\Intervenant;

use Zend\Permissions\Acl\Role\RoleInterface;
use Application\Entity\Db\Intervenant;
use Application\Acl\ComposanteRole;

/**
 * Description of PeutSaisirReferentielRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirReferentielRule extends IntervenantRule
{
    /**
     * Constructeur.
     * 
     * @param Intervenant   $intervenant Intervenant dont on saisit du référentiel
     * @param RoleInterface $role        Role auteur de la modification
     */
    public function __construct(Intervenant $intervenant, RoleInterface $role)
    {
        parent::__construct($intervenant);
        $this->setRole($role);
    }
    
    public function execute()
    {
        $estPermanent = new EstPermanentRule($this->getIntervenant());
        if (!$estPermanent->execute()) {
            $this->setMessage($estPermanent->getMessage());
            return false;
        }
        
        if (!$this->getRole() instanceof ComposanteRole) {
            return true;
        }
        
        $estAffecte = new EstAffecteRule($this->getIntervenant(), $this->getRole()->getStructure());
        if (!$estAffecte->execute()) {
            $this->setMessage(sprintf("%s %s étant votre structure de responsabilité.", $estAffecte->getMessage(), $this->getRole()->getStructure()));
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
    
    /**
     * @var RoleInterface
     */
    protected $role;
    /**
     * 
     * @param RoleInterface $role
     * @return self
     */
    public function setRole(RoleInterface $role)
    {
        $this->role = $role;
        return $this;
    }
    /**
     * @return RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }
}