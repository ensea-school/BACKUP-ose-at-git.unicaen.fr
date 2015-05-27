<?php

namespace Application\Rule\Validation;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Service\Workflow\WorkflowIntervenant;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Common\Exception\LogicException;

/**
 * Tentative de centralisation des "règles métier" concernant la validation des enseignements
 * ou de référentiel.
 * 
 * Détermine en fonction du contexte courant les paramètres nécessaires à la validation
 * des enseignements ou de référentiel.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class ValidationEnsRefAbstractRule implements WorkflowIntervenantAwareInterface
{
    use \Application\Service\Workflow\WorkflowIntervenantAwareTrait;
    use \UnicaenApp\Traits\MessageAwareTrait;
    
    /**
     * @var Intervenant
     */
    protected $intervenant;

    /**
     * @var TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;

    /**
     * @var Role
     */
    protected $role;

    /**
     * @var string
     */
    protected $privilege;
    
    /**
     * 
     * @param Intervenant $intervenant
     * @return self
     */
    public function setIntervenant(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;
        return $this;
    }

    /**
     * 
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @return self
     */
    public function setTypeVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire)
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        return $this;
    }

    /**
     * 
     * @param Role $role
     * @return self
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * 
     * @param string $privilege
     * @return self
     */
    public function setPrivilege($privilege)
    {
        $this->privilege = $privilege;
        return $this;
    }

    /**
     * Exécute la règle.
     * 
     * @return self
     */
    final public function execute()
    {
        if (!$this->intervenant) {
            throw new LogicException("Un intervenant doit être spécifié.");
        }
        if (!$this->typeVolumeHoraire) {
            throw new LogicException("Un type de volume horaire doit être spécifié.");
        }
        if (!$this->role) {
            throw new LogicException("Un rôle doit être spécifié.");
        }
        
        if (!in_array($this->typeVolumeHoraire->getCode(), TypeVolumeHoraire::$codes)) {
            throw new LogicException("Type de volume horaire spécifié inattendu.");
        }
        
        $this
                ->determineStructureRole()
                ->determineStructuresIntervention()
                ->determineStructureValidation();
        
        return $this;
    }

    /**
     * Détermine la structure associée au rôle utilisateur courant.
     * 
     * @return self
     * @throws LogicException
     */
    protected function determineStructureRole()
    {
        if ($this->role instanceof IntervenantRole) {
            $this->structureRole = $this->intervenant->getStructure();
        }
        else {
            $this->structureRole = $this->role->getStructure();
        }
        
        return $this;
    }

    /**
     * Détermine selon le contexte les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider.
     * 
     * @return self
     */
    abstract protected function determineStructuresIntervention();

    /**
     * Détermine la structure auteure de la validation à créer ou des validations existantes.
     * 
     * @return self
     */
    abstract protected function determineStructureValidation();

    /**
     * @var Structure
     */
    protected $structureRole;

    /**
     * Retourne la structure correspondant au rôle courant.
     * 
     * @return null|Structure
     */
    protected function getStructureRole()
    {
        return $this->structureRole;
    }

    /**
     * Composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider    
     * 
     * @var array Format <code>libellé => Structure</code> 
     * NB: la valeur <code>null</code> est possible (i.e. enseignement hors UCBN).
     */
    protected $structuresIntervention;

    /**
     * Retourne les composantes d'intervention (éventuelles) à utiliser comme
     * critère de recherche des enseignements déjà validés ou à valider    
     * 
     * @return array Format <code>libellé => Structure</code> 
     * NB: la valeur <code>null</code> est possible (i.e. enseignement hors UCBN).
     */
    public function getStructuresIntervention()
    {
        return $this->structuresIntervention;
    }

    /**
     * @var Structure
     */
    protected $structureValidation;

    /**
     * Retourne la structure auteure de la validation à créer ou des validations recherchées.
     * 
     * @return null|Structure
     */
    public function getStructureValidation()
    {
        return $this->structureValidation;
    }
    
    /**
     * Détermine si le rôle courant possède le privilège spécifié.
     * 
     * @param string $privilege Ex: 'create', 'read'
     * @return boolean
     */
    abstract public function isAllowed($privilege);
    
    /**
     * Indique si le privilège spécifié est accordé, en fonction de la progression 
     * de l'intervenant dans le workflow.
     * 
     * En consultation (privilège 'read'), laisse passer.
     * En création (privilège 'create'), vérifie que l'étape du workflow est atteignable.
     * 
     * @param string $privilege
     * @return boolean
     */
    protected function isAllowedByWorkflow($privilege) 
    {
        $step = $this->getWorkflow()->getStep($this->getWorkflowStepKey());
        
        /**
         * Consultation : no problemo.
         */
        if ($privilege === 'read') {
            return true;
        }
        
        /**
         * Création : l'étape du workflow doit être atteignable.
         */
        elseif ($privilege === 'create') {
            if (!$this->getWorkflow()->isStepReachable($step)) {
                $this->addMessage("Étape '{$step->getLabel()}' du workflow non atteignable.", 'warning');
                
                return false;
            }
        }
        
        /**
         * Modification, suppression : l'étape suivante du workflow ne doit pas avoir été franchie.
         */
//        elseif (in_array($privilege, ['update', 'delete'])) {
//            $nextStep = $this->getWorkflow()->getNextStep($step);
//            if ($nextStep && $this->getWorkflow()->isStepCrossable($nextStep)) {
//                return false;
//            }
//        }
        
        return true;
    }
    
    /**
     * Initialise et retourne le workflow intervenant.
     * 
     * @return WorkflowIntervenant
     */
    protected function getWorkflow()
    {
        $wf = $this->getWorkflowIntervenant()
                ->setIntervenant($this->intervenant)
                ->setRole($this->role);
        
        return $wf;
    }
}