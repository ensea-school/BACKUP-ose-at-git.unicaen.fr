<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantPermanent;

/**
 * Description of PeutSaisirModificationServiceDuRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirModificationServiceDuRule extends IntervenantRule
{
    /**
     * Constructeur.
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant Intervenant dont on modifie le service dû
     * @param \Application\Acl\ComposanteDbRole  $role        Role auteur de la modification
     */
    public function __construct(\Application\Entity\Db\Intervenant $intervenant, \Application\Acl\ComposanteDbRole $role)
    {
        parent::__construct($intervenant);
        $this->role = $role;
    }
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();
        
        if (!$this->getIntervenant() instanceof IntervenantPermanent || !$statut->estPermanent()) {
            $this->setMessage(sprintf("%s n'est pas un intervenant permanent."));
            return false;
        }
        
        if ($this->getIntervenant()->getStructure() !== $this->getRole()->getStructure() 
                && $this->getIntervenant()->getStructure()->getParenteNiv2() !== $this->getRole()->getStructure()->getParenteNiv2()) {
            $this->setMessage(
                    sprintf("L'intervenant permanent %s n'est pas affecté à votre structure de responsabilité (%s) ou à l'une de ses sous-structures.",
                            $this->getIntervenant(),
                            $this->getRole()->getStructure()));
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