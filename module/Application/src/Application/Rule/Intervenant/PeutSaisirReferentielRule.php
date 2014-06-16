<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Acl\ComposanteDbRole;

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
     * @param Intervenant      $intervenant Intervenant dont on saisit du référentiel
     * @param ComposanteDbRole $role        Role auteur de la modification
     */
    public function __construct(Intervenant $intervenant, ComposanteDbRole $role)
    {
        parent::__construct($intervenant);
        $this->role = $role;
    }
    
    public function execute()
    {
        $estPermanent = new EstPermanentRule($this->getIntervenant());
        if (!$estPermanent->execute()) {
            $this->setMessage($estPermanent->getMessage());
            return false;
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
     * @var \Application\Acl\ComposanteDbRole
     */
    protected $role;
    /**
     * @return \Application\Acl\ComposanteDbRole
     */
    public function getRole()
    {
        return $this->role;
    }
}